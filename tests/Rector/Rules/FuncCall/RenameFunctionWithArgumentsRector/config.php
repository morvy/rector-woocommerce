<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionWithArgumentsRector;
use Morvy\RectorWooCommerce\ValueObject\FunctionRenameWithArguments;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpstanConfig(__DIR__ . '/../../../../../config/phpstan-for-rector.neon');
    $rectorConfig->ruleWithConfiguration(RenameFunctionWithArgumentsRector::class, [
        new FunctionRenameWithArguments(
            'block_core_navigation_block_contains_core_navigation',
            'block_core_navigation_block_tree_has_block_type',
            ['core/navigation']
        ),
    ]);
};
