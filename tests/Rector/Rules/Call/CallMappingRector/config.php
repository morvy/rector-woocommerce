<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\Call\CallMappingRector;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(CallMappingRector::class, [
        new CallMapping(null, 'woocommerce_change_term_counts', null, 'wc_change_term_counts', [0, 1]),
        new CallMapping(null, 'woocommerce_readfile_chunked', 'WC_Download_Handler', 'readfile_chunked', [0]),
        new CallMapping('WC_Abstract_Legacy_Order', 'get_item_meta', null, 'get_metadata', [['literal' => 'order_item'], 0, 1, 2]),
        new CallMapping('WCAdminHelper', 'is_store_page', 'WCAdminHelper', 'is_current_page_store_page', []),
    ]);
};
