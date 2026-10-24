<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'WC_Settings_Rest_API' => 'WC_Settings_Advanced',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Widget_Product_Tag_Cloud', '_get_current_taxonomy', 'get_current_taxonomy'),
        new MethodCallRename('WC_Widget_Product_Tag_Cloud', '_topic_count_text', 'topic_count_text'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_settings_account_page_options → woocommerce_settings_advanced_page_options
     * - woocommerce_settings_account_page_options_after → woocommerce_settings_advanced_page_options_after
     * - woocommerce_settings_account_page_options_end → woocommerce_settings_advanced_page_options_end
     * - woocommerce_settings_checkout_page_options → woocommerce_settings_advanced_page_options
     * - woocommerce_settings_checkout_page_options_after → woocommerce_settings_advanced_page_options_after
     * - woocommerce_settings_checkout_page_options_end → woocommerce_settings_advanced_page_options_end
     *
     * CLASSES
     * - WC_Geo_IP
     *
     * FILTERS
     * - woocommerce_settings_api → woocommerce_settings_advanced
     * - woocommerce_settings_tabs_api → woocommerce_settings_tabs_advanced
     */
};
