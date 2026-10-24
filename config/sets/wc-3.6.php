<?php

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RemoveFuncCallRector::class, [
        'wc_taxonomy_metadata_update_content_for_split_terms',
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'delete_term' => 'WC_Admin_Taxonomies',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'add_woocommerce_term_meta'    => 'add_term_meta',
        'delete_woocommerce_term_meta' => 'delete_term_meta',
        'get_woocommerce_term_meta'    => 'get_term_meta',
        'update_woocommerce_term_meta' => 'update_term_meta',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        'woocommerce_add_to_cart_hash' => 'woocommerce_cart_hash',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * FUNCTIONS
     * - wc_get_min_max_price_meta_query
     */
};
