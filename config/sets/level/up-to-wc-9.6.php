<?php

use Morvy\RectorWooCommerce\Set\WooCommerceLevelSetList;
use Morvy\RectorWooCommerce\Set\WooCommerceSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([WooCommerceSetList::WC_9_6, WooCommerceLevelSetList::UP_TO_WC_9_5]);
};
