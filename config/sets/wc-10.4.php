<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Gateway_Paypal_Notices', 'add_paypal_migration_notice_on_payments_settings_page', 'add_paypal_notices_on_payments_settings_page'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * FUNCTIONS
     * - wc_enqueue_js
     */
};
