<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\Features\Features::add_features_section
     * - Automattic\WooCommerce\Admin\Features\Features::add_features_settings
     */
};
