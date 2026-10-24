<?php

use Automattic\WooCommerce\Utilities\FeaturesUtil;

class WC_Brands {}

if (!class_exists('WC_Brands_Coupons')) {
    class WC_Brands_Coupons {}
}

function wc_get_brands() {}

function feature_misc(WP_Post $post): void
{
    has_shortcode($post->post_content, 'woocommerce_checkout');
    wc_post_content_has_shortcode('woocommerce_cart');
    FeaturesUtil::declare_compatibility('product_block_editor', __FILE__, true);
    FeaturesUtil::feature_is_enabled('new_product_management_experience');

    // Fine.
    has_shortcode($post->post_content, 'woocommerce_my_account');
    FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
}

function shortcode_with_block_check(WP_Post $post): bool
{
    return has_shortcode($post->post_content, 'woocommerce_checkout') || has_block('woocommerce/checkout', $post);
}
