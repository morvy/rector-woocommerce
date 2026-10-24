<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReturnFirstArgumentRector::class, [
        'get'             => 'WP_Query',
        'format_settings' => 'WC_Settings_API',
    ]);
};
