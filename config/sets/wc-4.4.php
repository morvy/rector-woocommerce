<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector as MethodReturnFirstArgumentRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(MethodReturnFirstArgumentRector::class, [
        'sku_search' => 'WC_Admin_List_Table_Products',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - WC_Abstract_Legacy_Order::get_product_from_item
     */
};
