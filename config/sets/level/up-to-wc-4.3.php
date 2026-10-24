<?php

use Morvy\RectorWooCommerce\Set\WooCommerceLevelSetList;
use Morvy\RectorWooCommerce\Set\WooCommerceSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([WooCommerceSetList::WC_4_3, WooCommerceLevelSetList::UP_TO_WC_4_2]);
};
