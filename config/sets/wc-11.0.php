<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Blocks\QueryFilters
     * - WC_REST_Layout_Templates_Controller
     * - WC_REST_Products_Catalog_Controller
     *
     * METHODS
     * - Automattic\WooCommerce\Blocks\QueryFilters::add_query_clauses
     * - Automattic\WooCommerce\Blocks\QueryFilters::get_attribute_counts
     * - Automattic\WooCommerce\Blocks\QueryFilters::get_filtered_price
     * - Automattic\WooCommerce\Blocks\QueryFilters::get_rating_counts
     * - Automattic\WooCommerce\Blocks\QueryFilters::get_stock_status_counts
     * - Automattic\WooCommerce\Blocks\QueryFilters::init
     * - Automattic\WooCommerce\Blocks\QueryFilters::main_query_filter
     * - WC_Admin_Notices::add_min_version_notice
     */
};
