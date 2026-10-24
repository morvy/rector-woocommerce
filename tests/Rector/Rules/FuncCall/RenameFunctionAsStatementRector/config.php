<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionAsStatementRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RenameFunctionAsStatementRector::class, [
        'woocommerce_mail' => 'wc_mail',
    ]);
};
