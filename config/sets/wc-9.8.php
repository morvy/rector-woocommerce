<?php

use Morvy\RectorWooCommerce\Rules\Call\CallMappingRector;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(CallMappingRector::class, [
        new CallMapping('Automattic\WooCommerce\Admin\WCAdminHelper', 'is_store_page', 'Automattic\WooCommerce\Admin\WCAdminHelper', 'is_current_page_store_page', []),
    ]);
};
