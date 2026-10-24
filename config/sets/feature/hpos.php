<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\Hpos\OrderTypeComparisonRector;
use Morvy\RectorWooCommerce\Rules\Hpos\ScreenIdComparisonRector;
use Rector\Config\RectorConfig;

/*
 * Opt-in HPOS migration (not part of the level sets): rewrites order checks that only work with legacy order
 * storage into forms that work with both. Assumes WooCommerce is loaded where the code runs.
 * Everything else HPOS-related is reported by the PHPStan extension (extension.neon).
 */
return static function (RectorConfig $rectorConfig): void {
    // The WordPress/WooCommerce stubs, scanned (never executed), so they cannot clash with a real install or with
    // stubs the project loads itself.
    $rectorConfig->phpstanConfig(__DIR__ . '/../../phpstan-for-rector.neon');

    $rectorConfig->rule(OrderTypeComparisonRector::class);
    $rectorConfig->rule(ScreenIdComparisonRector::class);
};
