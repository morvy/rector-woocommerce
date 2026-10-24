<?php

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameHookRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameHookRector::class, [
        'woocommerce_admin_newly_installed'                       => 'woocommerce_newly_installed',
        'woocommerce_admin_updated'                               => 'woocommerce_updated',
        'woocommerce_product_csv_importer_check_import_file_path' => 'woocommerce_csv_importer_check_import_file_path',
    ]);
};
