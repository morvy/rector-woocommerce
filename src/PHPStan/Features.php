<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\PHPStan;

/**
 * WooCommerce features that replaced older mechanisms, with the version they became the default
 * and the extension points that stop working once they are on.
 *
 * Sources: WooCommerce 3.0.9 – 11.3 source and developer.woocommerce.com (HPOS recipe book, Cart and Checkout
 * blocks extensibility docs, Legacy REST API removal announcement).
 */
final class Features
{
    /**
     * HPOS (custom order tables) is enabled for new stores, WC_Install::maybe_enable_hpos().
     */
    public const HPOS = '8.2';

    /**
     * Cart and Checkout blocks are the default pages for new stores.
     */
    public const BLOCKS = '8.3';

    /**
     * The Legacy REST API moved out of core into the woocommerce-legacy-rest-api plugin.
     */
    public const LEGACY_API = '9.0';

    /**
     * Brands are part of core and always enabled (9.4–9.5 was a partial rollout).
     */
    public const BRANDS = '9.6';

    /**
     * The product block editor feature and its feature flags were removed.
     */
    public const PRODUCT_BLOCK_EDITOR_REMOVED = '11.0';

    /**
     * Order post types used by the legacy (wp_posts) order storage.
     */
    public const ORDER_POST_TYPES = ['shop_order', 'shop_order_refund', 'shop_order_placehold'];

    /**
     * Legacy screen ids of the order list and edit screens.
     */
    public const ORDER_SCREEN_IDS = ['shop_order', 'edit-shop_order', 'shop_order_refund'];

    /**
     * Meta keys the order data stores keep as order properties ($internal_meta_keys of the CPT and HPOS order stores
     * and the refund store). Under HPOS they live in the order tables, never in postmeta.
     * `_edit_lock` / `_edit_last` are left out: every post type uses them.
     */
    public const INTERNAL_ORDER_META_KEYS = [
        '_customer_user', '_order_key', '_order_currency',
        '_billing_first_name', '_billing_last_name', '_billing_company', '_billing_address_1', '_billing_address_2',
        '_billing_city', '_billing_state', '_billing_postcode', '_billing_country', '_billing_email', '_billing_phone',
        '_shipping_first_name', '_shipping_last_name', '_shipping_company', '_shipping_address_1', '_shipping_address_2',
        '_shipping_city', '_shipping_state', '_shipping_postcode', '_shipping_country', '_shipping_phone',
        '_completed_date', '_paid_date',
        '_cart_discount', '_cart_discount_tax', '_order_shipping', '_order_shipping_tax', '_order_tax', '_order_total',
        '_payment_method', '_payment_method_title', '_transaction_id', '_customer_ip_address', '_customer_user_agent',
        '_created_via', '_order_version', '_prices_include_tax', '_date_completed', '_date_paid', '_payment_tokens',
        '_billing_address_index', '_shipping_address_index',
        '_recorded_sales', '_recorded_coupon_usage_counts', '_download_permissions_granted',
        '_order_stock_reduced', '_new_order_email_sent', '_cogs_total_value',
        '_refund_amount', '_refunded_by', '_refunded_payment', '_refund_reason',
    ];

    /**
     * Classes removed from core with the Legacy REST API in 9.0.
     */
    public const LEGACY_API_CLASSES = [
        'WC_API', 'WC_Legacy_API', 'WC_API_Server', 'WC_API_Resource', 'WC_API_Authentication', 'WC_API_Exception',
        'WC_API_Handler', 'WC_API_JSON_Handler', 'WC_API_XML_Handler', 'WC_API_Orders', 'WC_API_Products',
        'WC_API_Coupons', 'WC_API_Customers', 'WC_API_Reports', 'WC_API_Taxes', 'WC_API_Webhooks',
        'WC_REST_Legacy_Orders_Controller', 'WC_REST_Legacy_Products_Controller', 'WC_REST_Legacy_Coupons_Controller',
    ];

    /**
     * Classes and functions WooCommerce core declares since Brands moved into core.
     */
    public const BRANDS_CLASSES   = ['WC_Brands', 'WC_Brands_Admin', 'WC_Brands_Coupons', 'WC_Brands_Brand_Settings_Manager', 'WC_Widget_Brand_Description', 'WC_Widget_Brand_Nav', 'WC_Widget_Brand_Thumbnails'];
    public const BRANDS_FUNCTIONS = ['wc_get_brands', 'wc_get_brand_thumbnail_image', 'wc_get_brand_thumbnail_url'];
}
