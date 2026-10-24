<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * ARGUMENTS
     * - WC_REST_Product_Variations_Controller::prepare_objects_query (local_attributes)
     */
};
