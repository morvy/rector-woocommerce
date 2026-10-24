<?php

/**
 * Plugin Name: Dirty
 * WC tested up to: 9.0.
 */
add_filter('manage_edit-shop_order_columns', 'dirty_columns');
add_action('woocommerce_checkout_update_order_meta', 'dirty_save', 10, 2);
add_filter('woocommerce_api_order_response', 'dirty_api');
add_action('save_post_shop_order', 'dirty_save');

class Dirty_Gateway extends WC_Payment_Gateway {}
