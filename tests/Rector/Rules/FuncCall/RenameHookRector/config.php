<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        'woocommerce_get_price' => 'woocommerce_product_get_price',
    ]);
};
