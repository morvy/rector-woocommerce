<?php

namespace Morvy\RectorWooCommerce\Rules\Hpos;

use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * `$screen->id === 'shop_order'` → `in_array( $screen->id, [ 'shop_order', wc_get_page_screen_id( 'shop-order' ) ], true )`.
 *
 * With legacy order storage wc_get_page_screen_id( 'shop-order' ) returns 'shop_order', so nothing changes; with HPOS
 * it adds the order screen ('woocommerce_page_wc-orders'). That one screen id covers both the HPOS order list and
 * edit screens, so a check meant for one of them now matches both under HPOS. Only WP_Screen ids are rewritten.
 */
final class ScreenIdComparisonRector extends AbstractRector
{
    // Only the edit screen: with legacy storage wc_get_page_screen_id( 'shop-order' ) is 'shop_order', so pairing it
    // with 'edit-shop_order' (the list) would also match the edit screen.
    private const LEGACY_SCREENS = ['shop_order'];

    public function getNodeTypes(): array
    {
        return [Identical::class, Equal::class, NotIdentical::class, NotEqual::class];
    }

    /**
     * @param Equal|Identical|NotEqual|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach (['left' => 'right', 'right' => 'left'] as $screenSide => $literalSide) {
            $screenId = $node->{$screenSide};
            $literal  = $node->{$literalSide};

            if (!$literal instanceof String_ || !in_array($literal->value, self::LEGACY_SCREENS, true) || !$this->isScreenId($screenId)) {
                continue;
            }

            $funcCall = new FuncCall(new Name('in_array'), [
                new Arg($screenId),
                new Arg(new Array_([
                    new ArrayItem($literal),
                    new ArrayItem(new FuncCall(new FullyQualified('wc_get_page_screen_id'), [new Arg(new String_('shop-order'))])),
                ])),
                new Arg(new ConstFetch(new Name('true'))),
            ]);

            return $node instanceof NotIdentical || $node instanceof NotEqual ? new BooleanNot($funcCall) : $funcCall;
        }

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Match the order screens under HPOS and legacy order storage',
            [
                new CodeSample(
                    "if (\$screen->id === 'shop_order') {}",
                    "if (in_array(\$screen->id, ['shop_order', \\wc_get_page_screen_id('shop-order')], true)) {}"
                ),
            ]
        );
    }

    private function isScreenId(Expr $expr): bool
    {
        if (!$expr instanceof PropertyFetch || !$this->isName($expr->name, 'id')) {
            return false;
        }

        return (new ObjectType('WP_Screen'))->isSuperTypeOf(TypeCombinator::removeNull($this->getType($expr->var)))->yes();
    }
}
