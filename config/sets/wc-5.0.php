<?php

use Morvy\RectorWooCommerce\Rules\StaticCall\RenameStaticMethodRector;
use Morvy\RectorWooCommerce\ValueObject\RenameStaticMethod;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\Admin\Loader', 'is_feature_enabled', 'Automattic\WooCommerce\Admin\Features\Features', 'is_enabled'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Blocks\Assets
     * - Automattic\WooCommerce\Blocks\Library
     */
};
