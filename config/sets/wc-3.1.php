<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\MethodCallToFuncCallRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Transform\ValueObject\MethodCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(MethodCallToFuncCallRector::class, [
        new MethodCallToFuncCall('Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore', 'get_orders', 'wc_get_orders'),
        new MethodCallToFuncCall('WC_Order_Data_Store_CPT', 'get_orders', 'wc_get_orders'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'wc_get_customer_avatar_url' => 'get_avatar_url',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - WC_Order_Item_Meta::__construct
     */
};
