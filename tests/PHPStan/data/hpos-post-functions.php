<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

function hpos_post_functions(WC_Order $order, WC_Product $product, int $order_id, int $post_id, WC_Order_Item $item): void
{
    get_post_meta($order->get_id(), '_my_key', true);
    update_post_meta($order_id, '_my_key', 1);
    delete_post_meta($item->get_order_id(), '_my_key');
    get_post_meta($post_id, '_billing_email', true);
    get_metadata('post', $order_id, '_my_key', true);
    get_post($order_id);
    wp_trash_post($order_id);
    get_edit_post_link($order->get_id());

    // Not orders.
    get_post_meta($post_id, '_my_key', true);
    get_post_meta($product->get_id(), '_billing_email', true);
    get_post_meta(get_the_ID(), '_order_total', true);
    get_metadata('user', $order_id, '_my_key', true);
    get_user_meta($order_id, '_billing_email', true);

    // Dual path: legacy branches are fine.
    if (OrderUtil::custom_orders_table_usage_is_enabled()) {
        $order->get_meta('_my_key');
    } else {
        get_post_meta($order_id, '_my_key', true);
    }
}

final class Hpos_Early_Return
{
    public static function meta(WC_Order $order): mixed
    {
        if (self::is_hpos_enabled()) {
            return $order->get_meta('_my_key');
        }

        return get_post_meta($order->get_id(), '_my_key', true);
    }

    private static function is_hpos_enabled(): bool
    {
        return true;
    }
}

function hpos_flag_in_variable(WC_Order $order, int $order_id, bool $hpos_enabled): void
{
    if ($hpos_enabled) {
        $order->get_meta('_my_key');
    } else {
        get_post_meta($order_id, '_my_key', true);
    }

    $using_hpos = true;

    if (!$using_hpos) {
        update_post_meta($order_id, '_my_key', 1);
    }
}

final class Hpos_Property_Flag
{
    private bool $use_hpos = true;

    public function save(int $order_id): void
    {
        if (!$this->use_hpos) {
            update_post_meta($order_id, '_my_key', 1);
        }
    }
}

function hpos_early_return_type_guard($order, int $order_id): void
{
    if (!$order instanceof WC_Order) {
        return;
    }

    update_post_meta($order_id, '_my_key', 1);
}

function hpos_untyped_order($order): void
{
    get_post_meta($order->get_id(), '_my_key', true);
}

function hpos_elseif_and_option(int $order_id): void
{
    if ($order_id < 0) {
        get_post_meta($order_id, '_my_key', true);
    } elseif (\Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()) {
    }

    if (get_option('woocommerce_custom_orders_table_enabled') !== 'yes') {
        get_post_meta($order_id, '_my_key', true);
    }

    $value = match (true) {
        \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() => null,
        default => get_post_meta($order_id, '_my_key', true),
    };
}
