<?php
/**
 * Plugin Name: Loop
 * WC tested up to: 9.0
 */

add_action('before_woocommerce_init', static function (): void {
    foreach (['custom_order_tables', 'cart_checkout_blocks'] as $feature) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility($feature, __FILE__, true);
    }
});

add_action('woocommerce_store_api_checkout_update_order_from_request', 'loop_save', 10, 2);
