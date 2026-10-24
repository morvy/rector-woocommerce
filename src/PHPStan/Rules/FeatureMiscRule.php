<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\NodeVisitor\CompatibilityGuardVisitor;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Smaller feature replacements:
 * - shortcode detection of the cart/checkout pages, false on block pages (8.3),
 * - classes/functions that WooCommerce core declares since Brands moved into core (9.6),
 * - feature flags of the removed product block editor (11.0),
 * - URLs to the legacy order list (edit.php?post_type=shop_order), not the HPOS list (8.2).
 *
 * @implements Rule<Node>
 */
final readonly class FeatureMiscRule implements Rule
{
    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        return match (true) {
            $node instanceof FuncCall                                                                         => $this->shortcode($node),
            $node instanceof StaticCall                                                                       => $this->featureKey($node),
            $node instanceof Class_, $node instanceof Function_                                               => $this->brands($node, $scope),
            $node instanceof String_ && $node->getAttribute(CompatibilityGuardVisitor::HPOS_GUARDED) !== true => $this->orderListUrl($node),
            default                                                                                           => [],
        };
    }

    /**
     * @return list<IdentifierRuleError>
     */
    private function shortcode(FuncCall $funcCall): array
    {
        if (!$funcCall->name instanceof Name || !$this->featureGate->isActive(Features::BLOCKS) || $funcCall->getAttribute(CompatibilityGuardVisitor::BLOCKS_GUARDED) === true) {
            return [];
        }

        $position = match ($funcCall->name->toLowerString()) {
            'has_shortcode'                 => 1,
            'wc_post_content_has_shortcode' => 0,
            default                         => null,
        };
        $tag = $position === null ? null : ($funcCall->getArgs()[$position]->value ?? null);

        if (!$tag instanceof String_ || !in_array($tag->value, ['woocommerce_cart', 'woocommerce_checkout'], true)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf('Detecting the %s page by its [%s] shortcode fails on the Cart/Checkout blocks, the default for new stores since WooCommerce 8.3.', mb_substr($tag->value, 12), $tag->value))
                ->identifier('woocommerce.blocks.shortcode')
                ->tip("Use is_cart() / is_checkout() (shortcode and block), or has_block( 'woocommerce/checkout' ).")
                ->build(),
        ];
    }

    /**
     * @return list<IdentifierRuleError>
     */
    private function featureKey(StaticCall $staticCall): array
    {
        if (!$staticCall->class instanceof Name || !str_ends_with(mb_strtolower($staticCall->class->toString()), 'featuresutil') || !$staticCall->name instanceof Identifier) {
            return [];
        }

        $key = $staticCall->getArgs()[0]->value ?? null;

        if (!$key instanceof String_ || !in_array($key->value, ['product_block_editor', 'new_product_management_experience'], true) || !$this->featureGate->isActive(Features::PRODUCT_BLOCK_EDITOR_REMOVED)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf("Feature '%s' was removed in WooCommerce 11.0; %s() no longer knows it.", $key->value, $staticCall->name->toString()))
                ->identifier('woocommerce.productBlockEditor')
                ->tip('Extend the classic product editor (woocommerce_product_data_tabs / woocommerce_product_data_panels) instead.')
                ->build(),
        ];
    }

    /**
     * @return list<IdentifierRuleError>
     */
    private function brands(Class_|Function_ $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier || !$this->featureGate->isActive(Features::BRANDS) || $node->getAttribute(CompatibilityGuardVisitor::EXISTS_GUARDED) === true) {
            return [];
        }

        $name = $node->name->toString();
        $list = $node instanceof Class_ ? Features::BRANDS_CLASSES : Features::BRANDS_FUNCTIONS;

        // Only global names collide with core's.
        if ($scope->getNamespace() !== null) {
            return [];
        }

        foreach ($list as $coreName) {
            if (strcasecmp($name, $coreName) === 0) {
                return [
                    RuleErrorBuilder::message(sprintf('%s %s is declared by WooCommerce core since 9.6 (Brands is part of core); declaring it again is a fatal error.', $node instanceof Class_ ? 'Class' : 'Function', $coreName))
                        ->identifier('woocommerce.brands')
                        ->tip('Rely on the core Brands feature, or wrap the declaration in a class_exists() / function_exists() check.')
                        ->build(),
                ];
            }
        }

        return [];
    }

    /**
     * @return list<IdentifierRuleError>
     */
    private function orderListUrl(String_ $string): array
    {
        if (!$this->featureGate->isActive(Features::HPOS) || preg_match('/(edit|post-new)\.php\?post_type=shop_order\b/', $string->value) !== 1) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Link to the legacy order screens (edit.php?post_type=shop_order); under HPOS (default for new stores since WooCommerce 8.2) the orders are at admin.php?page=wc-orders.')
                ->identifier('woocommerce.hpos.url')
                ->tip("Use admin_url( 'admin.php?page=wc-orders' ) when OrderUtil::custom_orders_table_usage_is_enabled(), or OrderUtil::get_order_admin_new_url() / \$order->get_edit_order_url().")
                ->build(),
        ];
    }
}
