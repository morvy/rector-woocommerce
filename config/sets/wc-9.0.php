<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * ARGUMENTS
     * - WC_Webhook::build_payload (legacy REST API version payload)
     *
     * FUNCTIONS
     * - get_woocommerce_api_url
     *
     * METHODS
     * - WC_Cart::is_coupon_emails_allowed
     */
};
