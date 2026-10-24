<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'set_icon' => 'Automattic\WooCommerce\Admin\Notes\Note',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\API\Plugins::install_plugin
     */
};
