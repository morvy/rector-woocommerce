<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - WC_Background_Emailer
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\Notes\Note::get_image
     * - Automattic\WooCommerce\Admin\Notes\Note::get_layout
     * - Automattic\WooCommerce\Admin\Notes\Note::set_image
     * - Automattic\WooCommerce\Admin\Notes\Note::set_layout
     * - WC_Admin_Webhooks_Table_List::get_legacy_api_webhooks_count
     */
};
