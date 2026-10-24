<?php
/**
 * Plugin Name: Wrapper
 * WC tested up to: 9.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

function wrapper_declare(string $feature, bool $compatible): void
{
    FeaturesUtil::declare_compatibility($feature, __FILE__, $compatible);
}

add_action('woocommerce_checkout_update_order_meta', 'wrapper_save');
