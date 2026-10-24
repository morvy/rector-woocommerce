<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\PropertyFetch\PropertyFetchToMethodCallRector;
use Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('WC_Order', 'id', 'get_id'),
        new PropertyFetchToMethodCall('WC_Order', 'billing_email', 'get_billing_email'),
    ]);
};
