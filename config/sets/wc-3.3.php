<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\MethodCallToFuncCallRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Rector\Config\RectorConfig;
use Rector\Transform\ValueObject\MethodCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(MethodCallToFuncCallRector::class, [
        new MethodCallToFuncCall('WC_Cart', 'get_item_data', 'wc_get_formatted_cart_item_data'),
        new MethodCallToFuncCall('WC_Cart', 'get_remove_url', 'wc_get_cart_remove_url'),
        new MethodCallToFuncCall('WC_Cart', 'get_undo_url', 'wc_get_cart_undo_url'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'get_delivery_log'                    => 'WC_Webhook',
        'process_product_file_download_paths' => 'WC_Admin_Post_Types',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - WC_REST_Webhook_Deliveries_V1_Controller
     * - WC_REST_Webhook_Deliveries_V2_Controller
     *
     * FUNCTIONS
     * - wc_get_core_supported_themes
     *
     * METHODS
     * - WC_Admin_Webhooks::get_logs_navigation
     * - WC_Admin_Webhooks::logs_output
     * - WC_Customer_Download_Data_Store::update_download_id
     * - WC_Post_Data::process_product_file_download_paths
     * - WC_Product_Download::get_previous_hash
     * - WC_Product_Download::set_previous_hash
     */
};
