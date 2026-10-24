<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PHPStan\Rules\IdentifierRuleError;
use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\OrderContext;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports code that recognises orders or the order screens by legacy post type / screen id:
 * - `add_meta_box( …, 'shop_order' )` / `remove_meta_box( …, 'shop_order', … )`
 * - `get_post_type( $id ) === 'shop_order'`
 * - `$screen->id === 'shop_order'`, `$typenow == 'shop_order'`, `$_GET['post_type'] === 'shop_order'`
 *
 * @implements Rule<Expr>
 */
final readonly class HposScreenRule implements Rule
{
    private const SCREEN_VARIABLES = ['typenow', 'post_type', 'screen_id', 'current_screen', 'screen', 'hook', 'hook_suffix'];

    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return Expr::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->featureGate->isActive(Features::HPOS) || OrderContext::isGuarded($node)) {
            return [];
        }

        if ($node instanceof FuncCall && $node->name instanceof Name) {
            return $this->checkMetaBox($node);
        }

        if (!$node instanceof Identical && !$node instanceof Equal && !$node instanceof NotIdentical && !$node instanceof NotEqual) {
            return [];
        }

        [$literal, $other] = $node->right instanceof String_ ? [$node->right, $node->left] : [$node->left, $node->right];

        if (!$literal instanceof String_ || !in_array($literal->value, [...Features::ORDER_SCREEN_IDS, ...Features::ORDER_POST_TYPES], true)) {
            return [];
        }

        if ($other instanceof FuncCall && $other->name instanceof Name && $other->name->toLowerString() === 'get_post_type' && $other->getArgs() !== []) {
            return [
                RuleErrorBuilder::message("get_post_type() does not identify HPOS orders (default for new stores since WooCommerce 8.2): their post is a 'shop_order_placehold' placeholder, or missing.")
                    ->identifier('woocommerce.hpos.postType')
                    ->tip('Use OrderUtil::get_order_type( $id ) or OrderUtil::is_order( $id, wc_get_order_types() ). Rector can rewrite this: WooCommerceFeatureSetList::HPOS.')
                    ->build(),
            ];
        }

        if ($other instanceof PropertyFetch && $other->name instanceof Identifier && $other->name->toLowerString() === 'post_type') {
            return [
                RuleErrorBuilder::message(sprintf("Checking a post's post_type against '%s': under HPOS (default for new stores since WooCommerce 8.2) orders are not posts, so this never matches an order.", $literal->value))
                    ->identifier('woocommerce.hpos.postType')
                    ->tip('Accept the order object instead (hooks like woocommerce_before_delete_order pass it), or use OrderUtil::is_order( $id, wc_get_order_types() ).')
                    ->build(),
            ];
        }

        if (!$this->isScreenExpr($other)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf("Screen check against '%s' only matches the legacy order screens; the HPOS order screens (default for new stores since WooCommerce 8.2) are 'woocommerce_page_wc-orders'.", $literal->value))
                ->identifier('woocommerce.hpos.screen')
                ->tip("Compare with wc_get_page_screen_id( 'shop-order' ), or use OrderUtil::is_order_edit_screen() / is_order_list_table_screen(). Rector can rewrite \$screen->id comparisons: WooCommerceFeatureSetList::HPOS.")
                ->build(),
        ];
    }

    /**
     * @return list<IdentifierRuleError>
     */
    private function checkMetaBox(FuncCall $funcCall): array
    {
        $position = match ($funcCall->name instanceof Name ? $funcCall->name->toLowerString() : '') {
            'add_meta_box'    => 3,
            'remove_meta_box' => 1,
            default           => null,
        };

        $screen = $position === null ? null : ($funcCall->getArgs()[$position]->value ?? null);

        if (!$screen instanceof Expr || !$this->isLegacyOrderScreenOnly($screen)) {
            return [];
        }

        return [
            RuleErrorBuilder::message("Meta box registered for the 'shop_order' screen does not appear on the HPOS order screen (default for new stores since WooCommerce 8.2).")
                ->identifier('woocommerce.hpos.metaBox')
                ->tip("Register it for wc_get_page_screen_id( 'shop-order' ) and accept both callback arguments: \$order = \$post_or_order instanceof WP_Post ? wc_get_order( \$post_or_order->ID ) : \$post_or_order;")
                ->build(),
        ];
    }

    /**
     * 'shop_order', or an array of screens that contains it but has no HPOS screen next to it.
     */
    private function isLegacyOrderScreenOnly(Expr $expr): bool
    {
        if ($expr instanceof String_) {
            return $expr->value === 'shop_order';
        }

        if (!$expr instanceof Array_) {
            return false;
        }

        $hasLegacy = false;

        foreach ($expr->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if ($item->value instanceof String_) {
                if (str_contains($item->value->value, 'wc-orders')) {
                    return false;
                }

                $hasLegacy = $hasLegacy || $item->value->value === 'shop_order';

                continue;
            }

            // A computed screen next to 'shop_order' (wc_get_page_screen_id(), OrderUtil::get_order_admin_screen()) covers HPOS.
            return false;
        }

        return $hasLegacy;
    }

    private function isScreenExpr(Expr $expr): bool
    {
        if ($expr instanceof PropertyFetch && $expr->name instanceof Identifier) {
            return $expr->name->toLowerString() === 'id';
        }

        if ($expr instanceof Variable && is_string($expr->name)) {
            return in_array($expr->name, self::SCREEN_VARIABLES, true);
        }

        return $expr instanceof ArrayDimFetch &&
            $expr->var instanceof Variable &&
            in_array($expr->var->name, ['_GET', '_REQUEST', '_POST'], true) &&
            $expr->dim instanceof String_ &&
            $expr->dim->value === 'post_type';
    }
}
