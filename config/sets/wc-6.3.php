<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\Admin\Loader', 'is_admin_or_embed_page', 'Automattic\WooCommerce\Admin\PageController', 'is_admin_or_embed_page'),
        new RenameStaticMethod('Automattic\WooCommerce\Admin\Loader', 'is_admin_page', 'Automattic\WooCommerce\Admin\PageController', 'is_admin_page'),
        new RenameStaticMethod('Automattic\WooCommerce\Admin\Loader', 'is_embed_page', 'Automattic\WooCommerce\Admin\PageController', 'is_embed_page'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\Features\Onboarding
     *
     * FILTERS
     * - woocommerce_rest_api_option_permissions
     *
     * FUNCTIONS
     * - Automattic\WooCommerce\Admin\Loader
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_allowed_industries
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_allowed_product_types
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_allowed_themes
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_product_data
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_theme_data
     * - Automattic\WooCommerce\Admin\Features\Onboarding::get_themes
     */
};
