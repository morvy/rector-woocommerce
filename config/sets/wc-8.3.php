<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * FILTERS
     * - woocommerce_cart_shortcode_tag → woocommerce_create_pages
     * - woocommerce_checkout_shortcode_tag → woocommerce_create_pages
     */
};
