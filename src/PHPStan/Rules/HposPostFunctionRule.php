<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\OrderContext;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports post and post-meta functions used on orders: under HPOS (default for new stores since 8.2) orders and their
 * meta live in the wc_orders tables, so these read nothing and write data WooCommerce never reads.
 *
 * An argument counts as an order when it is `$order->get_id()` (typed), a variable named like `$order_id`, or when
 * the meta key is one of WooCommerce's internal order keys.
 *
 * @implements Rule<FuncCall>
 */
final readonly class HposPostFunctionRule implements Rule
{
    /**
     * function => [id argument, meta key argument or null, kind].
     */
    private const FUNCTIONS = [
        'get_post_meta'          => [0, 1, 'read'],
        'get_post_custom'        => [0, null, 'read'],
        'get_post_custom_keys'   => [0, null, 'read'],
        'get_post_custom_values' => [1, 0, 'read'],
        'update_post_meta'       => [0, 1, 'write'],
        'add_post_meta'          => [0, 1, 'write'],
        'delete_post_meta'       => [0, 1, 'delete'],
        'get_metadata'           => [1, 2, 'read'],
        'metadata_exists'        => [1, 2, 'read'],
        'update_metadata'        => [1, 2, 'write'],
        'add_metadata'           => [1, 2, 'write'],
        'delete_metadata'        => [1, 2, 'delete'],
        'get_post'               => [0, null, 'post'],
        'get_post_status'        => [0, null, 'post'],
        'get_post_field'         => [1, null, 'post'],
        'wp_delete_post'         => [0, null, 'postWrite'],
        'wp_trash_post'          => [0, null, 'postWrite'],
        'wp_untrash_post'        => [0, null, 'postWrite'],
        'get_edit_post_link'     => [0, null, 'link'],
    ];
    private const MESSAGES = [
        'read'      => ['%s() reads order data from wp_postmeta, which is empty for orders under HPOS (default for new stores since WooCommerce 8.2).', 'Load the order and use $order->get_meta( $key ), or the getter for internal keys (e.g. $order->get_billing_email()).'],
        'write'     => ['%s() writes order data to wp_postmeta, which HPOS (default for new stores since WooCommerce 8.2) never reads.', 'Use $order->update_meta_data( $key, $value ) (or the setter for internal keys) followed by $order->save().'],
        'delete'    => ['%s() deletes order data from wp_postmeta only; under HPOS (default for new stores since WooCommerce 8.2) the order meta lives in wc_orders_meta.', 'Use $order->delete_meta_data( $key ) followed by $order->save().'],
        'post'      => ['%s() on an order reads wp_posts, which only holds a placeholder for orders under HPOS (default for new stores since WooCommerce 8.2).', 'Use wc_get_order( $id ) and the WC_Order getters (get_status(), get_date_created(), get_customer_note(), get_parent_id()).'],
        'postWrite' => ['%s() on an order bypasses the order data store; under HPOS (default for new stores since WooCommerce 8.2) the order is not changed.', 'Use $order->delete( $force_delete ), $order->update_status() or wc_get_order() + setters + save().'],
        'link'      => ['%s() on an order builds a post.php link, which is not the order screen under HPOS (default for new stores since WooCommerce 8.2).', 'Use $order->get_edit_order_url() or OrderUtil::get_order_admin_edit_url( $id ).'],
    ];

    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Name || !$this->featureGate->isActive(Features::HPOS) || OrderContext::isGuarded($node)) {
            return [];
        }

        $function = $node->name->toLowerString();

        if (!isset(self::FUNCTIONS[$function])) {
            return [];
        }

        [$idPosition, $keyPosition, $kind]  = self::FUNCTIONS[$function];
        $args                               = $node->getArgs();

        // get_metadata('post', …) and friends: only the post meta type concerns orders.
        if (str_contains($function, 'metadata') && (!($args[0]->value ?? null) instanceof String_ || $args[0]->value->value !== 'post')) {
            return [];
        }

        $id  = $args[$idPosition]->value ?? null;
        $key = $keyPosition === null ? null : ($args[$keyPosition]->value ?? null);

        if ($id === null) {
            return [];
        }

        $isOrder = OrderContext::isOrderId($id, $scope) ||
            (OrderContext::isInternalMetaKey($key) && !OrderContext::isClearlyNotOrder($id, $scope));

        if (!$isOrder) {
            return [];
        }

        [$message, $tip] = self::MESSAGES[$kind];

        return [
            RuleErrorBuilder::message(sprintf($message, $function))->identifier('woocommerce.hpos.post')->tip($tip)->build(),
        ];
    }
}
