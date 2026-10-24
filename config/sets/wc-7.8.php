<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - Automattic\WooCommerce\Blocks\BlockTemplatesController::get_block_templates_from_db
     */
};
