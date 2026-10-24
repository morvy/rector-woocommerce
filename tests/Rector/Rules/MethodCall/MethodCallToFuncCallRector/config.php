<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\MethodCall\MethodCallToFuncCallRector;
use Rector\Config\RectorConfig;
use Rector\Transform\ValueObject\MethodCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MethodCallToFuncCallRector::class, [
        new MethodCallToFuncCall('WC_Legacy_Cart', 'get_cart_url', 'wc_get_cart_url'),
    ]);
};
