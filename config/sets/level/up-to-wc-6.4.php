<?php

use Morvy\RectorWooCommerce\Set\WooCommerceLevelSetList;
use Morvy\RectorWooCommerce\Set\WooCommerceSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([WooCommerceSetList::WC_6_4, WooCommerceLevelSetList::UP_TO_WC_6_3]);
};
