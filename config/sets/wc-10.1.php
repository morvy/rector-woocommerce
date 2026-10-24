<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * FUNCTIONS
     * - wc_delete_related_product_transients
     *
     * METHODS
     * - Automattic\WooCommerce\Caches\OrderCountCache::get_default_statuses
     */
};
