<?php

namespace Morvy\RectorWooCommerce\Rules\Hpos;

use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * `get_post_type( $id ) === 'shop_order'` → `OrderUtil::get_order_type( $id ) === 'shop_order'`.
 *
 * With legacy order storage OrderUtil::get_order_type() is get_post_type() (the CPT data store), so nothing changes;
 * with HPOS it reads the orders table, where get_post_type() only sees a placeholder. Assumes WooCommerce 6.9+ is loaded.
 */
final class OrderTypeComparisonRector extends AbstractRector
{
    private const ORDER_TYPES = ['shop_order', 'shop_order_refund'];
    private const ORDER_UTIL  = 'Automattic\WooCommerce\Utilities\OrderUtil';

    public function getNodeTypes(): array
    {
        return [Identical::class, Equal::class, NotIdentical::class, NotEqual::class];
    }

    /**
     * @param Equal|Identical|NotEqual|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach (['left' => 'right', 'right' => 'left'] as $callSide => $literalSide) {
            $call    = $node->{$callSide};
            $literal = $node->{$literalSide};

            if (!$literal instanceof String_ || !in_array($literal->value, self::ORDER_TYPES, true) || !$this->isPostTypeOf($call)) {
                continue;
            }

            /** @var FuncCall $call */
            $node->{$callSide} = new StaticCall(new FullyQualified(self::ORDER_UTIL), 'get_order_type', $call->args);

            return $node;
        }

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Compare the order type through OrderUtil, which works with HPOS and legacy order storage',
            [
                new CodeSample(
                    "if (get_post_type(\$id) === 'shop_order') {}",
                    "if (\\Automattic\\WooCommerce\\Utilities\\OrderUtil::get_order_type(\$id) === 'shop_order') {}"
                ),
            ]
        );
    }

    /**
     * get_post_type( <id> ) with an explicit argument; without one it is the current post, which may be anything.
     */
    private function isPostTypeOf(Expr $expr): bool
    {
        return $expr instanceof FuncCall &&
            $expr->name instanceof Name &&
            $this->isName($expr->name, 'get_post_type') &&
            !$expr->isFirstClassCallable() &&
            count($expr->getArgs()) === 1 &&
            !$expr->getArgs()[0]->unpack;
    }
}
