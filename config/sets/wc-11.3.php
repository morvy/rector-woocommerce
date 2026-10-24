<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Internal\Abilities\AbilitiesRestBridge
     * - Automattic\WooCommerce\Internal\Abilities\REST\RestAbility
     * - Automattic\WooCommerce\Internal\Abilities\REST\RestAbilityFactory
     * - Automattic\WooCommerce\Internal\MCP\MCPAdapterProvider
     * - Automattic\WooCommerce\Internal\MCP\Transport\WooCommerceRestTransport
     *
     * FILTERS
     * - woocommerce_variable_product_taxes_influence_price
     *
     * METHODS
     * - Automattic\WooCommerce\Blocks\Assets\Api::get_block_metadata_path
     * - WC_Product_Variable_Data_Store_CPT::taxes_influence_price
     */
};
