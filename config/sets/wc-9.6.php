<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * ARGUMENTS
     * - Automattic\WooCommerce\Blocks\Domain\Package::__construct (FeatureGating)
     *
     * METHODS
     * - Automattic\WooCommerce\Blocks\Package::feature
     */
};
