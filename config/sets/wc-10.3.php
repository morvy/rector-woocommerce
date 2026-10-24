<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\Admin\API\Reports\Orders\DataStore', 'maybe_update_order_statuses_transient', 'Automattic\WooCommerce\Admin\API\Reports\Orders\DataStore', 'maybe_update_order_statuses_cache'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ARGUMENTS
     * - Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils::find_express_checkout_attributes (post_content as array)
     *
     * METHODS
     * - WC_Customer_Data_Store::get_user_ids_for_billing_email
     * - WC_Notes_Run_Db_Update::show_reminder
     */
};
