<?php

use Morvy\RectorWooCommerce\Rules\Call\CallMappingRector;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(CallMappingRector::class, [
        new CallMapping('Automattic\WooCommerce\Blocks\BlockTemplatesController', 'get_block_templates_from_db', 'Automattic\WooCommerce\Blocks\Utils\BlockTemplateUtils', 'get_block_templates_from_db', [0, 1]),
    ]);
};
