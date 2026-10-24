<?php

/**
 * Plugin Name: Clean
 * WC tested up to: 9.0.
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

add_action('before_woocommerce_init', static function (): void {
    FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
});

// Legacy hooks next to their HPOS counterparts.
add_filter('manage_edit-shop_order_columns', 'clean_columns');
add_filter('manage_' . wc_get_page_screen_id('shop-order') . '_columns', 'clean_columns');
add_filter('bulk_actions-edit-shop_order', 'clean_bulk');
add_filter('bulk_actions-woocommerce_page_wc-orders', 'clean_bulk');
add_action('save_post_shop_order', 'clean_save');
add_action('woocommerce_process_shop_order_meta', 'clean_save');

// Classic checkout hook, while the plugin also integrates with the blocks.
add_action('woocommerce_checkout_update_order_meta', 'clean_save');
add_action('woocommerce_store_api_checkout_update_order_from_request', 'clean_block_save', 10, 2);

class Clean_Gateway extends WC_Payment_Gateway {}

add_action('woocommerce_blocks_payment_method_type_registration', 'clean_register');
