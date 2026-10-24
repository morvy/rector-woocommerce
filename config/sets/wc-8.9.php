<?php

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionAsStatementRector;
use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameFunctionAsStatementRector::class, [
        '__experimental_woocommerce_blocks_register_checkout_field' => 'woocommerce_register_additional_checkout_field',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        '__experimental_woocommerce_blocks_sanitize_additional_field'        => 'woocommerce_sanitize_additional_field',
        '__experimental_woocommerce_blocks_validate_additional_field'        => 'woocommerce_validate_additional_field',
        '__experimental_woocommerce_blocks_validate_location_address_fields' => 'woocommerce_blocks_validate_location_address_fields',
        '__experimental_woocommerce_blocks_validate_location_contact_fields' => 'woocommerce_blocks_validate_location_contact_fields',
        '__experimental_woocommerce_blocks_validate_location_order_fields'   => 'woocommerce_blocks_validate_location_order_fields',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields', 'get_additional_fields_keys', 'get_order_fields_keys'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - __experimental_woocommerce_blocks_validate_location_{location}_fields → woocommerce_blocks_validate_location_{location}_fields
     * - woocommerce_blocks_validate_location_additional_fields
     *
     * ARGUMENTS
     * - Automattic\WooCommerce\Blocks\Assets\AssetDataRegistry::add ($check_key_exists)
     * - Automattic\WooCommerce\Blocks\Assets\AssetDataRegistry::hydrate_data_from_api_request ($check_key_exists)
     * - Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::get_group_key (group "additional")
     * - Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::get_group_name (group key "_wc_additional")
     * - Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::validate_options (location "additional")
     *
     * CONSTANTS
     * - Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields::ADDITIONAL_FIELDS_PREFIX
     */
};
