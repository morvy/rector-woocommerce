<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\Call\CallMappingRector;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(CallMappingRector::class, [
        new CallMapping(null, 'woocommerce_change_term_counts', null, 'wc_change_term_counts', [0, 1]),
        new CallMapping(null, 'woocommerce_readfile_chunked', 'WC_Download_Handler', 'readfile_chunked', [0]),
        new CallMapping('WC_Abstract_Legacy_Order', 'get_item_meta', null, 'get_metadata', [['literal' => 'order_item'], 0, 1, 2]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_price_including_tax', null, 'wc_get_price_including_tax', [['receiver' => true], ['array' => ['qty' => ['param' => 0, 'default' => 1], 'price' => ['param' => 1, 'default' => '']]]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_display_price', null, 'wc_get_price_to_display', [['receiver' => true], ['array' => ['qty' => ['param' => 1, 'default' => 1], 'price' => ['param' => 0, 'default' => '']]]]),
        new CallMapping('WC_Abstract_Legacy_Product', 'get_related', null, 'wc_get_related_products', [['receiverCall' => 'get_id'], 0]),
        new CallMapping('WC_Abstract_Legacy_Product', 'reduce_stock', null, 'wc_update_product_stock', [['receiver' => true], ['param' => 0, 'default' => 1], ['literal' => 'decrease']]),
        new CallMapping('WCAdminHelper', 'is_store_page', 'WCAdminHelper', 'is_current_page_store_page', []),
    ]);
};
