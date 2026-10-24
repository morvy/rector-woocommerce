<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Set;

/**
 * Opt-in migrations to WooCommerce features, separate from the version upgrade sets.
 */
final class WooCommerceFeatureSetList
{
    public const HPOS = __DIR__ . '/../../config/sets/feature/hpos.php';
}
