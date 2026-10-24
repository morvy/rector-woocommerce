<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\API\Reports\Categories\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Stats\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Customers\Stats\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Stats\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Stats\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Stats\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Query
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Stats\Query
     * - Automattic\WooCommerce\Admin\Features\Navigation\CoreMenu
     * - Automattic\WooCommerce\Admin\Features\Navigation\Menu
     * - Automattic\WooCommerce\Admin\Features\Navigation\Screen
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\API\Reports\Categories\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Categories\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Stats\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Coupons\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Customers\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Stats\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Downloads\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Stats\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Products\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Query::__construct
     * - Automattic\WooCommerce\Admin\API\Reports\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Stats\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Taxes\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Stats\Query::get_data
     * - Automattic\WooCommerce\Admin\API\Reports\Variations\Stats\Query::get_default_query_vars
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\Marketing::get_plugins
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\Marketing::has_installed_extensions
     */
};
