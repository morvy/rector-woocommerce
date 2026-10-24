<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'get'                      => 'WP_Query',
        'validate_settings_fields' => 'WC_Settings_API',
        'remove_taxes'             => 'WC_Legacy_Cart',
    ]);
};
