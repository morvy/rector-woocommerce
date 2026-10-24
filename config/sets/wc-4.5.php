<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\RestApi\Package', 'get_path', 'Automattic\WooCommerce\RestApi\Server', 'get_path'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\RestApi\Package
     *
     * METHODS
     * - Automattic\WooCommerce\Blocks\Assets::register_block_script
     * - Automattic\WooCommerce\RestApi\Package::get_version
     * - Automattic\WooCommerce\RestApi\Package::init
     */
};
