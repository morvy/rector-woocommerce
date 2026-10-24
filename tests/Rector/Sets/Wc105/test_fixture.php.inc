<?php

$wc0 = \WC_Gateway_Paypal_Constants::WPCOM_PROXY_REQUEST_TIMEOUT;
$wc1 = \WC_Gateway_Paypal_Constants::STATUS_COMPLETED;
$wc2 = \WC_Gateway_Paypal_Constants::STATUS_APPROVED;
$wc3 = \WC_Gateway_Paypal_Constants::STATUS_CAPTURED;
$wc4 = \WC_Gateway_Paypal_Constants::STATUS_AUTHORIZED;
$wc5 = \WC_Gateway_Paypal_Constants::STATUS_PAYER_ACTION_REQUIRED;
$wc6 = \WC_Gateway_Paypal_Constants::VOIDED;
$wc7 = \WC_Gateway_Paypal_Constants::INTENT_CAPTURE;
$wc8 = \WC_Gateway_Paypal_Constants::INTENT_AUTHORIZE;
$wc9 = \WC_Gateway_Paypal_Constants::PAYMENT_ACTION_CAPTURE;
$wc10 = \WC_Gateway_Paypal_Constants::PAYMENT_ACTION_AUTHORIZE;
$wc11 = \WC_Gateway_Paypal_Constants::SHIPPING_NO_SHIPPING;
$wc12 = \WC_Gateway_Paypal_Constants::SHIPPING_GET_FROM_FILE;
$wc13 = \WC_Gateway_Paypal_Constants::SHIPPING_SET_PROVIDED_ADDRESS;
$wc14 = \WC_Gateway_Paypal_Constants::USER_ACTION_PAY_NOW;
$wc15 = \WC_Gateway_Paypal_Constants::PAYPAL_ORDER_ITEM_NAME_MAX_LENGTH;
$wc16 = \WC_Gateway_Paypal_Constants::PAYPAL_INVOICE_ID_MAX_LENGTH;
$wc17 = \WC_Gateway_Paypal_Constants::PAYPAL_ADDRESS_LINE_MAX_LENGTH;
$wc18 = \WC_Gateway_Paypal_Constants::PAYPAL_COUNTRY_CODE_LENGTH;
$wc19 = \WC_Gateway_Paypal_Constants::PAYPAL_STATE_MAX_LENGTH;
$wc20 = \WC_Gateway_Paypal_Constants::PAYPAL_CITY_MAX_LENGTH;
$wc21 = \WC_Gateway_Paypal_Constants::PAYPAL_POSTAL_CODE_MAX_LENGTH;
$wc22 = \WC_Gateway_Paypal_Constants::PAYPAL_LOCALE_MAX_LENGTH;
$wc23 = \WC_Gateway_Paypal_Constants::PAYMENT_SOURCE_PAYPAL;
$wc24 = \WC_Gateway_Paypal_Constants::PAYMENT_SOURCE_VENMO;
$wc25 = \WC_Gateway_Paypal_Constants::PAYMENT_SOURCE_PAYLATER;
$wc26 = \WC_Gateway_Paypal_Constants::SUPPORTED_PAYMENT_SOURCES;
$wc27 = \WC_Gateway_Paypal_Constants::FIELDS_TO_REDACT;
$wc28 = \WC_Gateway_Paypal_Constants::SUPPORTED_CURRENCIES;
$wc29 = \WC_Gateway_Paypal_Notices::set_account_restriction_flag('foo');
$wc30 = \WC_Gateway_Paypal_Notices::clear_account_restriction_flag('foo');
$wc31 = \WC_Gateway_Paypal_Notices::manage_account_restriction_flag_for_notice('foo');
$wc32 = \WC_Gateway_Paypal_Helper::is_paypal_gateway_available('foo');
$wc33 = \WC_Gateway_Paypal_Helper::is_orders_v2_migration_eligible('foo');
$wc34 = \WC_Gateway_Paypal_Helper::redact_data('foo');
$wc35 = \WC_Gateway_Paypal_Helper::update_addresses_in_order('foo');

?>
-----
<?php

$wc0 = \Automattic\WooCommerce\Gateways\PayPal\Constants::WPCOM_PROXY_REQUEST_TIMEOUT;
$wc1 = \Automattic\WooCommerce\Gateways\PayPal\Constants::STATUS_COMPLETED;
$wc2 = \Automattic\WooCommerce\Gateways\PayPal\Constants::STATUS_APPROVED;
$wc3 = \Automattic\WooCommerce\Gateways\PayPal\Constants::STATUS_CAPTURED;
$wc4 = \Automattic\WooCommerce\Gateways\PayPal\Constants::STATUS_AUTHORIZED;
$wc5 = \Automattic\WooCommerce\Gateways\PayPal\Constants::STATUS_PAYER_ACTION_REQUIRED;
$wc6 = \Automattic\WooCommerce\Gateways\PayPal\Constants::VOIDED;
$wc7 = \Automattic\WooCommerce\Gateways\PayPal\Constants::INTENT_CAPTURE;
$wc8 = \Automattic\WooCommerce\Gateways\PayPal\Constants::INTENT_AUTHORIZE;
$wc9 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYMENT_ACTION_CAPTURE;
$wc10 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYMENT_ACTION_AUTHORIZE;
$wc11 = \Automattic\WooCommerce\Gateways\PayPal\Constants::SHIPPING_NO_SHIPPING;
$wc12 = \Automattic\WooCommerce\Gateways\PayPal\Constants::SHIPPING_GET_FROM_FILE;
$wc13 = \Automattic\WooCommerce\Gateways\PayPal\Constants::SHIPPING_SET_PROVIDED_ADDRESS;
$wc14 = \Automattic\WooCommerce\Gateways\PayPal\Constants::USER_ACTION_PAY_NOW;
$wc15 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_ORDER_ITEM_NAME_MAX_LENGTH;
$wc16 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_INVOICE_ID_MAX_LENGTH;
$wc17 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_ADDRESS_LINE_MAX_LENGTH;
$wc18 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_COUNTRY_CODE_LENGTH;
$wc19 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_STATE_MAX_LENGTH;
$wc20 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_CITY_MAX_LENGTH;
$wc21 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_POSTAL_CODE_MAX_LENGTH;
$wc22 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYPAL_LOCALE_MAX_LENGTH;
$wc23 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYMENT_SOURCE_PAYPAL;
$wc24 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYMENT_SOURCE_VENMO;
$wc25 = \Automattic\WooCommerce\Gateways\PayPal\Constants::PAYMENT_SOURCE_PAYLATER;
$wc26 = \Automattic\WooCommerce\Gateways\PayPal\Constants::SUPPORTED_PAYMENT_SOURCES;
$wc27 = \Automattic\WooCommerce\Gateways\PayPal\Constants::FIELDS_TO_REDACT;
$wc28 = \Automattic\WooCommerce\Gateways\PayPal\Constants::SUPPORTED_CURRENCIES;
$wc29 = \Automattic\WooCommerce\Gateways\PayPal\Notices::set_account_restriction_flag('foo');
$wc30 = \Automattic\WooCommerce\Gateways\PayPal\Notices::clear_account_restriction_flag('foo');
$wc31 = \Automattic\WooCommerce\Gateways\PayPal\Notices::manage_account_restriction_flag_for_notice('foo');
$wc32 = \Automattic\WooCommerce\Gateways\PayPal\Helper::is_paypal_gateway_available('foo');
$wc33 = \Automattic\WooCommerce\Gateways\PayPal\Helper::is_orders_v2_migration_eligible('foo');
$wc34 = \Automattic\WooCommerce\Gateways\PayPal\Helper::redact_data('foo');
$wc35 = \Automattic\WooCommerce\Gateways\PayPal\Helper::update_addresses_in_order('foo');

?>
