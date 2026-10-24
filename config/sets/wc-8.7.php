<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - __experimental_woocommerce_blocks_validate_additional_field → woocommerce_validate_additional_field
     *
     * FILTERS
     * - __experimental_woocommerce_blocks_sanitize_additional_field → woocommerce_sanitize_additional_field
     */
};
