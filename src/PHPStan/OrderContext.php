<?php

namespace Morvy\RectorWooCommerce\PHPStan;

use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Name;
use Morvy\RectorWooCommerce\PHPStan\NodeVisitor\CompatibilityGuardVisitor;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

/**
 * Decides whether an expression is (very likely) an order id, so HPOS rules only report order data.
 */
final class OrderContext
{
    private const ORDER_ID_NAME  = '/^(order_?id|orderid|wc_order_?id|refund_?id|parent_order_?id)$/i';
    private const ORDER_VARIABLE = '/^(the_|wc_)?(order|refund)$/i';

    public static function isGuarded(Node $node): bool
    {
        return $node->getAttribute(CompatibilityGuardVisitor::HPOS_GUARDED) === true;
    }

    /**
     * `$order->get_id()`, `wc_get_order( … )->get_id()`, `$item->get_order_id()`, `$order_id`, `$this->order_id`.
     */
    public static function isOrderId(Expr $expr, Scope $scope): bool
    {
        if ($expr instanceof Int_) {
            return self::isOrderId($expr->expr, $scope);
        }

        if ($expr instanceof MethodCall && $expr->name instanceof Identifier) {
            $method = $expr->name->toLowerString();
            $type   = $scope->getType($expr->var);

            if (in_array($method, ['get_id', 'get_parent_id'], true)) {
                $isOrder = (new ObjectType('WC_Abstract_Order'))->isSuperTypeOf($type);

                // Untyped `$order->get_id()`: trust the variable name unless the type says otherwise.
                return $isOrder->yes() || (!$isOrder->no() && $expr->var instanceof Variable && is_string($expr->var->name) && preg_match(self::ORDER_VARIABLE, $expr->var->name) === 1);
            }

            if ($method === 'get_order_id') {
                return (new ObjectType('WC_Order_Item'))->isSuperTypeOf($type)->yes();
            }

            return false;
        }

        if ($expr instanceof Variable && is_string($expr->name)) {
            return preg_match(self::ORDER_ID_NAME, $expr->name) === 1;
        }

        if ($expr instanceof PropertyFetch && $expr->name instanceof Identifier) {
            return preg_match(self::ORDER_ID_NAME, $expr->name->toString()) === 1;
        }

        return false;
    }

    /**
     * A product, user or the current post: not an order, even when the meta key would suggest it.
     */
    public static function isClearlyNotOrder(Expr $expr, Scope $scope): bool
    {
        if ($expr instanceof FuncCall && $expr->name instanceof Name) {
            return in_array($expr->name->toLowerString(), ['get_the_id', 'get_current_user_id'], true);
        }

        if ($expr instanceof MethodCall && $expr->name instanceof Identifier && $expr->name->toLowerString() === 'get_id') {
            return (new ObjectType('WC_Product'))->isSuperTypeOf($scope->getType($expr->var))->yes();
        }

        return false;
    }

    public static function isInternalMetaKey(?Expr $expr): bool
    {
        return $expr instanceof String_ && in_array($expr->value, Features::INTERNAL_ORDER_META_KEYS, true);
    }
}
