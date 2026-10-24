<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\Blocks\Utils\ProductGalleryUtils', 'get_product_gallery_image_count', 'Automattic\WooCommerce\Blocks\Utils\ProductGalleryUtils', 'get_product_gallery_media_count'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_coupon_object_updated_props → woocommerce_coupon_updated_props
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\API\OnboardingProducts
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\Features\Features::disable
     * - Automattic\WooCommerce\Admin\Features\Features::enable
     * - Automattic\WooCommerce\Admin\Features\Features::get_optional_feature_options
     * - Automattic\WooCommerce\Caches\ProductCountCache::set_multiple
     * - Automattic\WooCommerce\Caches\ProductCountCacheService::update_on_new_product
     */
};
