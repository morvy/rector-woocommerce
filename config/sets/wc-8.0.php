<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - Automattic\WooCommerce\Database\Migrations\TableMigrator::process_migration_batch_for_ids
     */
};
