<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('WC_Cache_Helper', 'incr_cache_prefix', 'WC_Cache_Helper', 'invalidate_cache_group'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - WC_Geolite_Integration
     *
     * FILTERS
     * - woocommerce_geolocation_local_database_path → woocommerce_maxmind_geolocation_database_path
     * - woocommerce_geolocation_update_database_periodically → woocommerce_maxmind_geolocation_update_database_periodically
     *
     * METHODS
     * - WC_Geolite_Integration::get_country_iso
     * - WC_Geolocation::get_local_database_path
     * - WC_Geolocation::init
     * - WC_Geolocation::update_database
     */
};
