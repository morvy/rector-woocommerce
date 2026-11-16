<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\StaticCall\RenameStaticMethodRector;
use Morvy\RectorWooCommerce\ValueObject\RenameStaticMethod;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('WC_Cache_Helper', 'incr_cache_prefix', 'WC_Cache_Helper', 'invalidate_cache_group'),
        new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'redact_data', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'redact_data'),
    ]);
};
