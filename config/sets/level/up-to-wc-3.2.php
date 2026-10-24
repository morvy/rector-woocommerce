<?php

use Morvy\RectorWooCommerce\Set\WooCommerceLevelSetList;
use Morvy\RectorWooCommerce\Set\WooCommerceSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([WooCommerceSetList::WC_3_2, WooCommerceLevelSetList::UP_TO_WC_3_1]);
};
