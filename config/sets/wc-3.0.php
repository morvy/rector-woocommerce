<?php

use Morvy\RectorWooCommerce\Rules\Call\CallMappingRector;
use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionAsStatementRector;
use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\MethodCallToFuncCallRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector as MethodReturnFirstArgumentRector;
use Morvy\RectorWooCommerce\Rules\PropertyFetch\PropertyFetchToMethodCallRector;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\ValueObject\MethodCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(CallMappingRector::class, [
        new CallMapping('WC_Abstract_Legacy_Order', 'decrease_coupon_usage_counts', null, 'wc_update_coupon_usage_counts', [['receiverCall' => 'get_id']]),
        new CallMapping('WC_Abstract_Legacy_Order', 'email_order_items_table', null, 'wc_get_email_order_items', [['receiver' => true], 0]),
        new CallMapping('WC_Abstract_Legacy_Order', 'get_item_meta', null, 'get_metadata', [['literal' => 'order_item'], 0, 1, 2]),
        new CallMapping('WC_Abstract_Legacy_Order', 'increase_coupon_usage_counts', null, 'wc_update_coupon_usage_counts', [['receiverCall' => 'get_id']]),
        new CallMapping('WC_Abstract_Legacy_Order', 'record_product_sales', null, 'wc_update_total_sales_counts', [['receiverCall' => 'get_id']]),
        new CallMapping('WC_Abstract_Legacy_Order', 'reduce_order_stock', null, 'wc_reduce_stock_levels', [['receiverCall' => 'get_id']]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_categories', null, 'wc_get_product_category_list', [['receiverCall' => 'get_id'], 0, 1, 2]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_display_price', null, 'wc_get_price_to_display', [['receiver' => true], ['array' => ['qty' => ['param' => 1, 'default' => 1], 'price' => ['param' => 0, 'default' => '']]]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_formatted_variation_attributes', null, 'wc_get_formatted_variation', [['receiver' => true], 0]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_price_excluding_tax', null, 'wc_get_price_excluding_tax', [['receiver' => true], ['array' => ['qty' => ['param' => 0, 'default' => 1], 'price' => ['param' => 1, 'default' => '']]]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_price_including_tax', null, 'wc_get_price_including_tax', [['receiver' => true], ['array' => ['qty' => ['param' => 0, 'default' => 1], 'price' => ['param' => 1, 'default' => '']]]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_rating_html', null, 'wc_get_rating_html', [['param' => 0, 'default' => null]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_related', null, 'wc_get_related_products', [['receiverCall' => 'get_id'], 0]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_tags', null, 'wc_get_product_tag_list', [['receiverCall' => 'get_id'], 0, 1, 2]),
        new CallMapping('WC_Abstract_Legacy_Product', 'increase_stock', null, 'wc_update_product_stock', [['receiver' => true], ['param' => 0, 'default' => 1], ['literal' => 'increase']]),
        new CallMapping('WC_Abstract_Legacy_Product', 'list_attributes', null, 'wc_display_product_attributes', [['receiver' => true]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'reduce_stock', null, 'wc_update_product_stock', [['receiver' => true], ['param' => 0, 'default' => 1], ['literal' => 'decrease']]),
        new CallMapping('WC_Abstract_Legacy_Product', 'set_stock', null, 'wc_update_product_stock', [['receiver' => true], 0, 1]),
        new CallMapping(null, 'woocommerce_change_term_counts', null, 'wc_change_term_counts', [0, 1]),
        new CallMapping(null, 'woocommerce_nav_menu_item_classes', null, 'wc_nav_menu_item_classes', [0]),
        new CallMapping(null, 'woocommerce_nav_menu_items', null, 'wc_nav_menu_items', [0]),
        new CallMapping(null, 'woocommerce_readfile_chunked', 'WC_Download_Handler', 'readfile_chunked', [0]),
    ]);

    $rectorConfig->ruleWithConfiguration(MethodCallToFuncCallRector::class, [
        new MethodCallToFuncCall('WC_Abstract_Legacy_Product', 'get_child', 'wc_get_product'),
        new MethodCallToFuncCall('WC_Abstract_Legacy_Product', 'get_price_html_from_text', 'wc_get_price_html_from_text'),
        new MethodCallToFuncCall('WC_Legacy_Cart', 'coupons_enabled', 'wc_coupons_enabled'),
        new MethodCallToFuncCall('WC_Legacy_Cart', 'get_cart_url', 'wc_get_cart_url'),
        new MethodCallToFuncCall('WC_Legacy_Cart', 'get_checkout_url', 'wc_get_checkout_url'),
        new MethodCallToFuncCall('WC_Legacy_Cart', 'ship_to_billing_address_only', 'wc_ship_to_billing_address_only'),
    ]);

    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('WC_Coupon', 'amount', 'get_amount'),
        new PropertyFetchToMethodCall('WC_Coupon', 'code', 'get_code'),
        new PropertyFetchToMethodCall('WC_Coupon', 'coupon_amount', 'get_amount'),
        new PropertyFetchToMethodCall('WC_Coupon', 'customer_email', 'get_email_restrictions'),
        new PropertyFetchToMethodCall('WC_Coupon', 'discount_type', 'get_discount_type'),
        new PropertyFetchToMethodCall('WC_Coupon', 'exclude_product_categories', 'get_excluded_product_categories'),
        new PropertyFetchToMethodCall('WC_Coupon', 'exclude_product_ids', 'get_excluded_product_ids'),
        new PropertyFetchToMethodCall('WC_Coupon', 'id', 'get_id'),
        new PropertyFetchToMethodCall('WC_Coupon', 'limit_usage_to_x_items', 'get_limit_usage_to_x_items'),
        new PropertyFetchToMethodCall('WC_Coupon', 'maximum_amount', 'get_maximum_amount'),
        new PropertyFetchToMethodCall('WC_Coupon', 'minimum_amount', 'get_minimum_amount'),
        new PropertyFetchToMethodCall('WC_Coupon', 'product_categories', 'get_product_categories'),
        new PropertyFetchToMethodCall('WC_Coupon', 'product_ids', 'get_product_ids'),
        new PropertyFetchToMethodCall('WC_Coupon', 'type', 'get_discount_type'),
        new PropertyFetchToMethodCall('WC_Coupon', 'usage_count', 'get_usage_count'),
        new PropertyFetchToMethodCall('WC_Coupon', 'usage_limit', 'get_usage_limit'),
        new PropertyFetchToMethodCall('WC_Coupon', 'usage_limit_per_user', 'get_usage_limit_per_user'),
        new PropertyFetchToMethodCall('WC_Customer', 'address', 'get_billing_address_1'),
        new PropertyFetchToMethodCall('WC_Customer', 'address_1', 'get_billing_address_1'),
        new PropertyFetchToMethodCall('WC_Customer', 'address_2', 'get_billing_address_2'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_address_1', 'get_billing_address_1'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_address_2', 'get_billing_address_2'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_city', 'get_billing_city'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_company', 'get_billing_company'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_country', 'get_billing_country'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_email', 'get_billing_email'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_first_name', 'get_billing_first_name'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_last_name', 'get_billing_last_name'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_phone', 'get_billing_phone'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_postcode', 'get_billing_postcode'),
        new PropertyFetchToMethodCall('WC_Customer', 'billing_state', 'get_billing_state'),
        new PropertyFetchToMethodCall('WC_Customer', 'calculated_shipping', 'get_calculated_shipping'),
        new PropertyFetchToMethodCall('WC_Customer', 'city', 'get_billing_city'),
        new PropertyFetchToMethodCall('WC_Customer', 'country', 'get_billing_country'),
        new PropertyFetchToMethodCall('WC_Customer', 'id', 'get_id'),
        new PropertyFetchToMethodCall('WC_Customer', 'is_vat_exempt', 'get_is_vat_exempt'),
        new PropertyFetchToMethodCall('WC_Customer', 'postcode', 'get_billing_postcode'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_address', 'get_shipping_address_1'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_address_1', 'get_shipping_address_1'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_address_2', 'get_shipping_address_2'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_city', 'get_shipping_city'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_company', 'get_shipping_company'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_country', 'get_shipping_country'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_first_name', 'get_shipping_first_name'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_last_name', 'get_shipping_last_name'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_postcode', 'get_shipping_postcode'),
        new PropertyFetchToMethodCall('WC_Customer', 'shipping_state', 'get_shipping_state'),
        new PropertyFetchToMethodCall('WC_Customer', 'state', 'get_billing_state'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_address_1', 'get_billing_address_1'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_address_2', 'get_billing_address_2'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_city', 'get_billing_city'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_company', 'get_billing_company'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_country', 'get_billing_country'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_email', 'get_billing_email'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_first_name', 'get_billing_first_name'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_last_name', 'get_billing_last_name'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_phone', 'get_billing_phone'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_postcode', 'get_billing_postcode'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_state', 'get_billing_state'),
        new PropertyFetchToMethodCall('WC_Order', 'cart_discount', 'get_total_discount'),
        new PropertyFetchToMethodCall('WC_Order', 'cart_discount_tax', 'get_discount_tax'),
        new PropertyFetchToMethodCall('WC_Order', 'cart_hash', 'get_cart_hash'),
        new PropertyFetchToMethodCall('WC_Order', 'created_via', 'get_created_via'),
        new PropertyFetchToMethodCall('WC_Order', 'customer_ip_address', 'get_customer_ip_address'),
        new PropertyFetchToMethodCall('WC_Order', 'customer_message', 'get_customer_note'),
        new PropertyFetchToMethodCall('WC_Order', 'customer_note', 'get_customer_note'),
        new PropertyFetchToMethodCall('WC_Order', 'customer_user', 'get_customer_id'),
        new PropertyFetchToMethodCall('WC_Order', 'customer_user_agent', 'get_customer_user_agent'),
        new PropertyFetchToMethodCall('WC_Order', 'id', 'get_id'),
        new PropertyFetchToMethodCall('WC_Order', 'order_currency', 'get_currency'),
        new PropertyFetchToMethodCall('WC_Order', 'order_key', 'get_order_key'),
        new PropertyFetchToMethodCall('WC_Order', 'order_shipping', 'get_shipping_total'),
        new PropertyFetchToMethodCall('WC_Order', 'order_shipping_tax', 'get_shipping_tax'),
        new PropertyFetchToMethodCall('WC_Order', 'order_tax', 'get_cart_tax'),
        new PropertyFetchToMethodCall('WC_Order', 'order_total', 'get_total'),
        new PropertyFetchToMethodCall('WC_Order', 'order_type', 'get_type'),
        new PropertyFetchToMethodCall('WC_Order', 'order_version', 'get_version'),
        new PropertyFetchToMethodCall('WC_Order', 'payment_method', 'get_payment_method'),
        new PropertyFetchToMethodCall('WC_Order', 'payment_method_title', 'get_payment_method_title'),
        new PropertyFetchToMethodCall('WC_Order', 'prices_include_tax', 'get_prices_include_tax'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_address_1', 'get_shipping_address_1'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_address_2', 'get_shipping_address_2'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_city', 'get_shipping_city'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_company', 'get_shipping_company'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_country', 'get_shipping_country'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_first_name', 'get_shipping_first_name'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_last_name', 'get_shipping_last_name'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_postcode', 'get_shipping_postcode'),
        new PropertyFetchToMethodCall('WC_Order', 'shipping_state', 'get_shipping_state'),
        new PropertyFetchToMethodCall('WC_Order', 'status', 'get_status'),
        new PropertyFetchToMethodCall('WC_Order', 'transaction_id', 'get_transaction_id'),
        new PropertyFetchToMethodCall('WC_Order', 'user_id', 'get_customer_id'),
        new PropertyFetchToMethodCall('WC_Product', 'backorders', 'get_backorders'),
        new PropertyFetchToMethodCall('WC_Product', 'crosssell_ids', 'get_cross_sell_ids'),
        new PropertyFetchToMethodCall('WC_Product', 'download_expiry', 'get_download_expiry'),
        new PropertyFetchToMethodCall('WC_Product', 'download_limit', 'get_download_limit'),
        new PropertyFetchToMethodCall('WC_Product', 'height', 'get_height'),
        new PropertyFetchToMethodCall('WC_Product', 'length', 'get_length'),
        new PropertyFetchToMethodCall('WC_Product', 'price', 'get_price'),
        new PropertyFetchToMethodCall('WC_Product', 'product_image_gallery', 'get_gallery_image_ids'),
        new PropertyFetchToMethodCall('WC_Product', 'product_type', 'get_type'),
        new PropertyFetchToMethodCall('WC_Product', 'purchase_note', 'get_purchase_note'),
        new PropertyFetchToMethodCall('WC_Product', 'regular_price', 'get_regular_price'),
        new PropertyFetchToMethodCall('WC_Product', 'reviews_allowed', 'get_reviews_allowed'),
        new PropertyFetchToMethodCall('WC_Product', 'sale_price', 'get_sale_price'),
        new PropertyFetchToMethodCall('WC_Product', 'shipping_class', 'get_shipping_class'),
        new PropertyFetchToMethodCall('WC_Product', 'sku', 'get_sku'),
        new PropertyFetchToMethodCall('WC_Product', 'stock_quantity', 'get_stock_quantity'),
        new PropertyFetchToMethodCall('WC_Product', 'stock_status', 'get_stock_status'),
        new PropertyFetchToMethodCall('WC_Product', 'tax_class', 'get_tax_class'),
        new PropertyFetchToMethodCall('WC_Product', 'tax_status', 'get_tax_status'),
        new PropertyFetchToMethodCall('WC_Product', 'total_sales', 'get_total_sales'),
        new PropertyFetchToMethodCall('WC_Product', 'upsell_ids', 'get_upsell_ids'),
        new PropertyFetchToMethodCall('WC_Product', 'visibility', 'get_catalog_visibility'),
        new PropertyFetchToMethodCall('WC_Product', 'weight', 'get_weight'),
        new PropertyFetchToMethodCall('WC_Product', 'width', 'get_width'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveFuncCallRector::class, [
        'wc_products_rss_feed',
        'wc_taxonomy_metadata_wpdbfix',
        'woocommerce_admin_scripts',
        'woocommerce_compile_less_styles',
        'woocommerce_datepicker_js',
        'woocommerce_taxonomy_metadata_wpdbfix',
        'woocommerce_tooltip_js',
        'woocommerce_weekend_area_js',
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'apply_cart_discounts_after_tax'    => 'WC_Legacy_Cart',
        'apply_product_discounts_after_tax' => 'WC_Legacy_Cart',
        'check_stock_status'                => 'WC_Abstract_Legacy_Product',
        'get_discounts_after_tax'           => 'WC_Legacy_Cart',
        'get_products_in_view'              => 'WC_Query',
        'grouped_product_sync'              => 'WC_Abstract_Legacy_Product',
        'layered_nav_init'                  => 'WC_Query',
        'layered_nav_query'                 => 'WC_Query',
        'send_stock_notifications'          => 'WC_Abstract_Legacy_Order',
        'set_default_data'                  => 'WC_Legacy_Customer',
        'set_type'                          => 'WC_Legacy_Payment_Token',
        'validate_settings_fields'          => 'WC_Settings_API',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'get_product'                                   => 'wc_get_product',
        'woocommerce_add_order_item'                    => 'wc_add_order_item',
        'woocommerce_add_order_item_meta'               => 'wc_add_order_item_meta',
        'woocommerce_array_overlay'                     => 'wc_array_overlay',
        'woocommerce_cart_totals_shipping_method_label' => 'wc_cart_totals_shipping_method_label',
        'woocommerce_clean'                             => 'wc_clean',
        'woocommerce_create_new_customer'               => 'wc_create_new_customer',
        'woocommerce_create_page'                       => 'wc_create_page',
        'woocommerce_customer_bought_product'           => 'wc_customer_bought_product',
        'woocommerce_customer_edit_account_url'         => 'wc_customer_edit_account_url',
        'woocommerce_customer_has_capability'           => 'wc_customer_has_capability',
        'woocommerce_date_format'                       => 'wc_date_format',
        'woocommerce_delete_order_item'                 => 'wc_delete_order_item',
        'woocommerce_delete_order_item_meta'            => 'wc_delete_order_item_meta',
        'woocommerce_disable_admin_bar'                 => 'wc_disable_admin_bar',
        'woocommerce_downloadable_file_permission'      => 'wc_downloadable_file_permission',
        'woocommerce_format_decimal'                    => 'wc_format_decimal',
        'woocommerce_get_attachment_image_attributes'   => 'wc_get_attachment_image_attributes',
        'woocommerce_get_dimension'                     => 'wc_get_dimension',
        'woocommerce_get_endpoint_url'                  => 'wc_get_endpoint_url',
        'woocommerce_get_featured_product_ids'          => 'wc_get_featured_product_ids',
        'woocommerce_get_filename_from_url'             => 'wc_get_filename_from_url',
        'woocommerce_get_formatted_variation'           => 'wc_get_formatted_variation',
        'woocommerce_get_order_id_by_order_key'         => 'wc_get_order_id_by_order_key',
        'woocommerce_get_order_item_meta'               => 'wc_get_order_item_meta',
        'woocommerce_get_page_id'                       => 'wc_get_page_id',
        'woocommerce_get_product_ids_on_sale'           => 'wc_get_product_ids_on_sale',
        'woocommerce_get_weight'                        => 'wc_get_weight',
        'woocommerce_let_to_num'                        => 'wc_let_to_num',
        'woocommerce_list_pages'                        => 'wc_list_pages',
        'woocommerce_locate_template'                   => 'wc_locate_template',
        'woocommerce_lostpassword_url'                  => 'wc_lostpassword_url',
        'woocommerce_order_terms'                       => 'wc_reorder_terms',
        'woocommerce_placeholder_img'                   => 'wc_placeholder_img',
        'woocommerce_placeholder_img_src'               => 'wc_placeholder_img_src',
        'woocommerce_prepare_attachment_for_js'         => 'wc_prepare_attachment_for_js',
        'woocommerce_price'                             => 'wc_price',
        'woocommerce_processing_order_count'            => 'wc_processing_order_count',
        'woocommerce_product_dropdown_categories'       => 'wc_product_dropdown_categories',
        'woocommerce_product_post_type_link'            => 'wc_product_post_type_link',
        'woocommerce_protected_product_add_to_cart'     => 'wc_protected_product_add_to_cart',
        'woocommerce_round_tax_total'                   => 'wc_round_tax_total',
        'woocommerce_sanitize_taxonomy_name'            => 'wc_sanitize_taxonomy_name',
        'woocommerce_set_term_order'                    => 'wc_set_term_order',
        'woocommerce_terms_clauses'                     => 'wc_terms_clauses',
        'woocommerce_time_format'                       => 'wc_time_format',
        'woocommerce_timezone_string'                   => 'wc_timezone_string',
        'woocommerce_trim_zeros'                        => 'wc_trim_zeros',
        'woocommerce_update_new_customer_past_orders'   => 'wc_update_new_customer_past_orders',
        'woocommerce_update_order_item_meta'            => 'wc_update_order_item_meta',
        'woocommerce_walk_category_dropdown_tree'       => 'wc_walk_category_dropdown_tree',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionAsStatementRector::class, [
        '_woocommerce_term_recount'                    => '_wc_term_recount',
        'woocommerce_add_to_cart_message'              => 'wc_add_to_cart_message',
        'woocommerce_cancel_unpaid_orders'             => 'wc_cancel_unpaid_orders',
        'woocommerce_cart_totals_coupon_html'          => 'wc_cart_totals_coupon_html',
        'woocommerce_cart_totals_fee_html'             => 'wc_cart_totals_fee_html',
        'woocommerce_cart_totals_order_total_html'     => 'wc_cart_totals_order_total_html',
        'woocommerce_cart_totals_shipping_html'        => 'wc_cart_totals_shipping_html',
        'woocommerce_cart_totals_subtotal_html'        => 'wc_cart_totals_subtotal_html',
        'woocommerce_clear_cart_after_payment'         => 'wc_clear_cart_after_payment',
        'woocommerce_downloadable_product_permissions' => 'wc_downloadable_product_permissions',
        'woocommerce_empty_cart'                       => 'wc_empty_cart',
        'woocommerce_get_template'                     => 'wc_get_template',
        'woocommerce_get_template_part'                => 'wc_get_template_part',
        'woocommerce_mail'                             => 'wc_mail',
        'woocommerce_paying_customer'                  => 'wc_paying_customer',
        'woocommerce_recount_after_stock_change'       => 'wc_recount_after_stock_change',
        'woocommerce_scheduled_sales'                  => 'wc_scheduled_sales',
        'woocommerce_set_customer_auth_cookie'         => 'wc_set_customer_auth_cookie',
        'woocommerce_show_messages'                    => 'wc_print_notices',
        'woocommerce_track_product_view'               => 'wc_track_product_view',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        'add_to_cart_fragments'                      => 'woocommerce_add_to_cart_fragments',
        'add_to_cart_redirect'                       => 'woocommerce_add_to_cart_redirect',
        'default_checkout_country'                   => 'default_checkout_billing_country',
        'default_checkout_postcode'                  => 'default_checkout_billing_postcode',
        'default_checkout_state'                     => 'default_checkout_billing_state',
        'wocommerce_credit_card_type_labels'         => 'woocommerce_credit_card_type_labels',
        'woocommerce_create_product_variation'       => 'woocommerce_new_product_variation',
        'woocommerce_debug_posting'                  => 'woocommerce_system_status_environment_rows',
        'woocommerce_email_order_schema_markup'      => 'woocommerce_structured_data_order',
        'woocommerce_get_currency'                   => 'woocommerce_order_get_currency',
        'woocommerce_get_price'                      => 'woocommerce_product_get_price',
        'woocommerce_get_product_attributes'         => 'woocommerce_product_get_attributes',
        'woocommerce_get_regular_price'              => 'woocommerce_product_get_regular_price',
        'woocommerce_get_sale_price'                 => 'woocommerce_product_get_sale_price',
        'woocommerce_get_sku'                        => 'woocommerce_product_get_sku',
        'woocommerce_get_stock_quantity'             => 'woocommerce_product_get_stock_quantity',
        'woocommerce_order_amount_cart_tax'          => 'woocommerce_order_get_cart_tax',
        'woocommerce_order_amount_discount_tax'      => 'woocommerce_order_get_discount_tax',
        'woocommerce_order_amount_discount_total'    => 'woocommerce_order_get_discount_total',
        'woocommerce_order_amount_shipping_tax'      => 'woocommerce_order_get_shipping_tax',
        'woocommerce_order_amount_shipping_total'    => 'woocommerce_order_get_shipping_total',
        'woocommerce_order_amount_subtotal'          => 'woocommerce_order_get_subtotal',
        'woocommerce_order_amount_total'             => 'woocommerce_order_get_total',
        'woocommerce_order_amount_total_discount'    => 'woocommerce_order_get_total_discount',
        'woocommerce_order_amount_total_tax'         => 'woocommerce_order_get_total_tax',
        'woocommerce_order_tax_totals'               => 'woocommerce_order_get_tax_totals',
        'woocommerce_payment_token_created'          => 'woocommerce_new_payment_token',
        'woocommerce_product_files'                  => 'woocommerce_product_get_downloads',
        'woocommerce_product_gallery_attachment_ids' => 'woocommerce_product_get_gallery_image_ids',
        'woocommerce_product_height'                 => 'woocommerce_product_get_height',
        'woocommerce_product_length'                 => 'woocommerce_product_get_length',
        'woocommerce_product_review_count'           => 'woocommerce_product_get_review_count',
        'woocommerce_product_tax_class'              => 'woocommerce_product_get_tax_class',
        'woocommerce_product_weight'                 => 'woocommerce_product_get_weight',
        'woocommerce_product_width'                  => 'woocommerce_product_get_width',
        'woocommerce_product_write_panels'           => 'woocommerce_product_data_panels',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Abstract_Legacy_Product', 'get_files', 'get_downloads'),
        new MethodCallRename('WC_Abstract_Legacy_Product', 'get_gallery_attachment_ids', 'get_gallery_image_ids'),
        new MethodCallRename('WC_Abstract_Legacy_Product', 'get_variation_description', 'get_description'),
        new MethodCallRename('WC_Abstract_Legacy_Product', 'get_variation_id', 'get_id'),
        new MethodCallRename('WC_Abstract_Legacy_Product', 'parent_is_visible', 'is_visible'),
        new MethodCallRename('WC_Legacy_Coupon', 'enable_free_shipping', 'get_free_shipping'),
        new MethodCallRename('WC_Legacy_Coupon', 'exclude_sale_items', 'get_exclude_sale_items'),
        new MethodCallRename('WC_Legacy_Customer', 'calculated_shipping', 'set_calculated_shipping'),
        new MethodCallRename('WC_Legacy_Customer', 'get_address', 'get_billing_address_1'),
        new MethodCallRename('WC_Legacy_Customer', 'get_address_2', 'get_billing_address_2'),
        new MethodCallRename('WC_Legacy_Customer', 'get_city', 'get_billing_city'),
        new MethodCallRename('WC_Legacy_Customer', 'get_country', 'get_billing_country'),
        new MethodCallRename('WC_Legacy_Customer', 'get_postcode', 'get_billing_postcode'),
        new MethodCallRename('WC_Legacy_Customer', 'get_state', 'get_billing_state'),
        new MethodCallRename('WC_Legacy_Customer', 'set_address', 'set_billing_address'),
        new MethodCallRename('WC_Legacy_Customer', 'set_address_2', 'set_billing_address_2'),
        new MethodCallRename('WC_Legacy_Customer', 'set_city', 'set_billing_city'),
        new MethodCallRename('WC_Legacy_Customer', 'set_country', 'set_billing_country'),
        new MethodCallRename('WC_Legacy_Customer', 'set_postcode', 'set_billing_postcode'),
        new MethodCallRename('WC_Legacy_Customer', 'set_shipping_to_base', 'set_shipping_address_to_base'),
        new MethodCallRename('WC_Legacy_Customer', 'set_state', 'set_billing_state'),
        new MethodCallRename('WC_Legacy_Customer', 'set_to_base', 'set_billing_address_to_base'),
        new MethodCallRename('WC_Legacy_Shipping_Zone', 'get_zone_id', 'get_id'),
        new MethodCallRename('WC_Order_Refund', 'get_refund_amount', 'get_amount'),
        new MethodCallRename('WC_Order_Refund', 'get_refund_reason', 'get_reason'),
    ]);

    $rectorConfig->ruleWithConfiguration(MethodReturnFirstArgumentRector::class, [
        'expand_item_meta' => 'WC_Abstract_Legacy_Order',
        'format_settings'  => 'WC_Settings_API',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_add_order_fee_meta → woocommerce_new_order_item
     * - woocommerce_add_order_item_meta → woocommerce_new_order_item
     * - woocommerce_add_shipping_order_item → woocommerce_new_order_item
     * - woocommerce_duplicate_product → woocommerce_product_duplicate
     * - woocommerce_log_add
     * - woocommerce_order_add_coupon → woocommerce_new_order_item
     * - woocommerce_order_add_fee → woocommerce_new_order_item
     * - woocommerce_order_add_product → woocommerce_new_order_item
     * - woocommerce_order_add_shipping → woocommerce_new_order_item
     * - woocommerce_order_add_tax → woocommerce_new_order_item
     * - woocommerce_order_edit_product → woocommerce_update_order_item
     * - woocommerce_order_items_table → woocommerce_order_details_after_order_table_items (fired directly by order-details.php template overrides older than 3.3)
     * - woocommerce_order_update_coupon → woocommerce_update_order_item
     * - woocommerce_order_update_fee → woocommerce_update_order_item
     * - woocommerce_order_update_shipping → woocommerce_update_order_item
     * - woocommerce_order_update_tax → woocommerce_update_order_item
     * - woocommerce_variable_product_sync → woocommerce_variable_product_sync_data, woocommerce_new_product or woocommerce_update_product
     *
     * CLASSES
     * - Automattic\WooCommerce\Blocks\Utils\BlocksWpQuery
     * - WC_Order_Item_Meta
     * - WC_Shipping_Legacy_Flat_Rate
     * - WC_Shipping_Legacy_Free_Shipping
     * - WC_Shipping_Legacy_International_Delivery
     * - WC_Shipping_Legacy_Local_Delivery
     * - WC_Shipping_Legacy_Local_Pickup
     *
     * FILTERS
     * - woocommerce_my_account_my_orders_columns → woocommerce_account_orders_columns (still fired directly by templates/myaccount/my-orders.php)
     * - woocommerce_refund_amount → woocommerce_order_refund_get_amount
     * - woocommerce_refund_reason → woocommerce_order_refund_get_reason
     * - {handle}_params → woocommerce_get_script_data
     *
     * FUNCTIONS
     * - woocommerce_format_hex (pluggable)
     * - woocommerce_format_total
     * - woocommerce_get_formatted_product_name
     * - woocommerce_get_product_schema
     * - woocommerce_get_product_terms
     * - woocommerce_hex_darker (pluggable)
     * - woocommerce_hex_lighter (pluggable)
     * - woocommerce_light_or_dark (pluggable)
     * - woocommerce_load_persistent_cart
     * - woocommerce_product_reviews_tab (pluggable)
     * - woocommerce_rgb_from_hex (pluggable)
     *
     * METHODS
     * - ActionScheduler_TimezoneHelper::get_local_timezone
     * - WC_Abstract_Legacy_Order::add_coupon
     * - WC_Abstract_Legacy_Order::add_fee
     * - WC_Abstract_Legacy_Order::add_shipping
     * - WC_Abstract_Legacy_Order::add_tax
     * - WC_Abstract_Legacy_Order::cancel_order
     * - WC_Abstract_Legacy_Order::display_item_downloads
     * - WC_Abstract_Legacy_Order::display_item_meta
     * - WC_Abstract_Legacy_Order::get_download_url
     * - WC_Abstract_Legacy_Order::get_item_downloads
     * - WC_Abstract_Legacy_Order::get_item_meta_array
     * - WC_Abstract_Legacy_Order::get_order
     * - WC_Abstract_Legacy_Order::has_meta
     * - WC_Abstract_Legacy_Order::populate
     * - WC_Abstract_Legacy_Order::update_coupon
     * - WC_Abstract_Legacy_Order::update_fee
     * - WC_Abstract_Legacy_Order::update_product
     * - WC_Abstract_Legacy_Order::update_shipping
     * - WC_Abstract_Legacy_Order::update_tax
     * - WC_Abstract_Legacy_Product::adjust_price
     * - WC_Abstract_Legacy_Product::enable_dimensions_display
     * - WC_Abstract_Legacy_Product::get_matching_variation
     * - WC_Abstract_Legacy_Product::get_parent
     * - WC_Abstract_Legacy_Product::get_post_data
     * - WC_Abstract_Legacy_Product::get_price_html_from_to
     * - WC_Abstract_Legacy_Product::get_total_stock
     * - WC_Abstract_Legacy_Product::get_variation_default_attributes
     * - WC_Abstract_Legacy_Product::has_all_attributes_set
     * - WC_Abstract_Legacy_Product::has_default_attributes
     * - WC_Abstract_Legacy_Product::sync_average_rating
     * - WC_Abstract_Legacy_Product::sync_rating_count
     * - WC_Abstract_Legacy_Product::variable_product_sync
     * - WC_Cart::get_discounts_before_tax
     * - WC_Cart::get_order_discount_total
     * - WC_Emails::order_schema_markup
     * - WC_Legacy_Cart::get_discounts_before_tax
     * - WC_Legacy_Cart::get_order_discount_total
     * - WC_Legacy_Coupon::apply_before_tax
     * - WC_Legacy_Coupon::format_array
     * - WC_Legacy_Customer::get_default_country
     * - WC_Legacy_Customer::get_default_state
     * - WC_Legacy_Customer::is_paying_customer
     * - WC_Legacy_Payment_Token::create
     * - WC_Legacy_Payment_Token::read
     * - WC_Legacy_Payment_Token::update
     * - WC_Legacy_Shipping_Zone::create
     * - WC_Legacy_Shipping_Zone::read
     * - WC_Legacy_Shipping_Zone::update
     * - WC_Logger::clear
     * - WC_Order_Refund::get_refund
     * - WC_Payment_Gateway::credit_card_form
     * - WC_Shipping::sort_shipping_methods
     */
};
