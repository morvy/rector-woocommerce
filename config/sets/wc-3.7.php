<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Abstract_Legacy_Order', 'get_used_coupons', 'get_coupon_codes'),
        new MethodCallRename('WC_Emails', 'email_footer_replace_site_title', 'replace_placeholders'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_before_cart_item_quantity_zero → woocommerce_remove_cart_item
     *
     * METHODS
     * - WC_Tax::maybe_remove_tax_class_rates
     */
};
