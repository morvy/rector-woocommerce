<?php

function hpos_queries(WC_Order $order): void
{
    global $wpdb;

    new WP_Query(['post_type' => 'shop_order', 'posts_per_page' => 10]);
    get_posts(['post_type' => ['shop_order', 'shop_order_refund']]);
    wp_insert_post(['post_type' => 'shop_order', 'post_status' => 'wc-pending']);
    $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'shop_order'");
    $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_billing_email' AND post_id = %d", 1));

    // Fine.
    wc_get_orders(['post_type' => 'shop_order', 'limit' => 10]);
    new WC_Order_Query(['post_type' => 'shop_order']);
    new WP_Query(['post_type' => 'product']);
    $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product'");
}

function hpos_queries_more(): void
{
    global $wpdb;

    $args = ['post_type' => 'shop_order', 'limit' => 5];
    wc_get_orders($args);
    wc_get_orders(array_merge(['post_type' => 'shop_order'], []));
    $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wc_orders WHERE type = 'shop_order'");
    $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_order_stats WHERE status = 'wc-completed'");
    $wpdb->get_results('SELECT * FROM ' . \Automattic\WooCommerce\Utilities\OrderUtil::get_table_for_orders() . " WHERE type = 'shop_order'");
}
