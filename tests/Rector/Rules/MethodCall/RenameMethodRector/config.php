<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Legacy_Cart', 'init', 'get_cart_from_session'),
    ]);
};
