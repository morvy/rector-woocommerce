<?php

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        '__experimental_woocommerce_blocks_sanitize_additional_field' => 'woocommerce_sanitize_additional_field',
        '__experimental_woocommerce_blocks_validate_additional_field' => 'woocommerce_validate_additional_field',
    ]);
};
