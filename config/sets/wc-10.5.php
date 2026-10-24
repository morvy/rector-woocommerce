<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'FIELDS_TO_REDACT', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'FIELDS_TO_REDACT'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'INTENT_AUTHORIZE', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'INTENT_AUTHORIZE'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'INTENT_CAPTURE', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'INTENT_CAPTURE'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYMENT_ACTION_AUTHORIZE', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYMENT_ACTION_AUTHORIZE'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYMENT_ACTION_CAPTURE', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYMENT_ACTION_CAPTURE'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYMENT_SOURCE_PAYLATER', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYMENT_SOURCE_PAYLATER'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYMENT_SOURCE_PAYPAL', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYMENT_SOURCE_PAYPAL'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYMENT_SOURCE_VENMO', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYMENT_SOURCE_VENMO'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_ADDRESS_LINE_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_ADDRESS_LINE_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_CITY_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_CITY_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_COUNTRY_CODE_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_COUNTRY_CODE_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_INVOICE_ID_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_INVOICE_ID_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_LOCALE_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_LOCALE_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_ORDER_ITEM_NAME_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_ORDER_ITEM_NAME_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_POSTAL_CODE_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_POSTAL_CODE_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'PAYPAL_STATE_MAX_LENGTH', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'PAYPAL_STATE_MAX_LENGTH'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'SHIPPING_GET_FROM_FILE', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'SHIPPING_GET_FROM_FILE'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'SHIPPING_NO_SHIPPING', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'SHIPPING_NO_SHIPPING'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'SHIPPING_SET_PROVIDED_ADDRESS', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'SHIPPING_SET_PROVIDED_ADDRESS'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'STATUS_APPROVED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'STATUS_APPROVED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'STATUS_AUTHORIZED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'STATUS_AUTHORIZED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'STATUS_CAPTURED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'STATUS_CAPTURED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'STATUS_COMPLETED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'STATUS_COMPLETED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'STATUS_PAYER_ACTION_REQUIRED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'STATUS_PAYER_ACTION_REQUIRED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'SUPPORTED_CURRENCIES', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'SUPPORTED_CURRENCIES'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'SUPPORTED_PAYMENT_SOURCES', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'SUPPORTED_PAYMENT_SOURCES'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'USER_ACTION_PAY_NOW', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'USER_ACTION_PAY_NOW'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'VOIDED', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'VOIDED'),
        new RenameClassAndConstFetch('WC_Gateway_Paypal_Constants', 'WPCOM_PROXY_REQUEST_TIMEOUT', 'Automattic\WooCommerce\Gateways\PayPal\Constants', 'WPCOM_PROXY_REQUEST_TIMEOUT'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'is_orders_v2_migration_eligible', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'is_orders_v2_migration_eligible'),
        new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'is_paypal_gateway_available', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'is_paypal_gateway_available'),
        new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'redact_data', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'redact_data'),
        new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'update_addresses_in_order', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'update_addresses_in_order'),
        new RenameStaticMethod('WC_Gateway_Paypal_Notices', 'clear_account_restriction_flag', 'Automattic\WooCommerce\Gateways\PayPal\Notices', 'clear_account_restriction_flag'),
        new RenameStaticMethod('WC_Gateway_Paypal_Notices', 'manage_account_restriction_flag_for_notice', 'Automattic\WooCommerce\Gateways\PayPal\Notices', 'manage_account_restriction_flag_for_notice'),
        new RenameStaticMethod('WC_Gateway_Paypal_Notices', 'set_account_restriction_flag', 'Automattic\WooCommerce\Gateways\PayPal\Notices', 'set_account_restriction_flag'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - WC_Gateway_Paypal_Buttons
     * - WC_Gateway_Paypal_Buttons → Automattic\WooCommerce\Gateways\PayPal\Buttons
     * - WC_Gateway_Paypal_Constants
     * - WC_Gateway_Paypal_Constants → Automattic\WooCommerce\Gateways\PayPal\Constants
     * - WC_Gateway_Paypal_Helper
     * - WC_Gateway_Paypal_Helper → Automattic\WooCommerce\Gateways\PayPal\Helper
     * - WC_Gateway_Paypal_Notices
     * - WC_Gateway_Paypal_Notices → Automattic\WooCommerce\Gateways\PayPal\Notices
     * - WC_Gateway_Paypal_Transact_Account_Manager
     * - WC_Gateway_Paypal_Transact_Account_Manager → Automattic\WooCommerce\Gateways\PayPal\TransactAccountManager
     * - WC_Gateway_Paypal_Webhook_Handler
     *
     * METHODS
     * - WC_Gateway_Paypal_Buttons::get_client_id
     * - WC_Gateway_Paypal_Buttons::get_common_options
     * - WC_Gateway_Paypal_Buttons::get_current_page_for_app_switch
     * - WC_Gateway_Paypal_Buttons::get_options
     * - WC_Gateway_Paypal_Buttons::get_page_type
     * - WC_Gateway_Paypal_Buttons::is_enabled
     * - WC_Gateway_Paypal_Helper::get_wc_order_from_paypal_custom_id
     * - WC_Gateway_Paypal_Helper::mask_email
     * - WC_Gateway_Paypal_Notices::add_paypal_migration_notice
     * - WC_Gateway_Paypal_Notices::add_paypal_notices
     * - WC_Gateway_Paypal_Notices::add_paypal_notices_on_payments_settings_page
     * - WC_Gateway_Paypal_Request::authorize_or_capture_payment
     * - WC_Gateway_Paypal_Request::capture_authorized_payment
     * - WC_Gateway_Paypal_Request::create_paypal_order
     * - WC_Gateway_Paypal_Request::fetch_paypal_client_id
     * - WC_Gateway_Paypal_Request::get_paypal_order_details
     * - WC_Gateway_Paypal_Request::get_paypal_order_purchase_unit_amount
     * - WC_Gateway_Paypal_Transact_Account_Manager::do_onboarding
     * - WC_Gateway_Paypal_Transact_Account_Manager::get_transact_account_data
     * - WC_Gateway_Paypal_Webhook_Handler::process_webhook
     */
};
