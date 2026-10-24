<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\PHPStan;

/**
 * Decides whether a feature check applies to the WooCommerce version the project targets.
 *
 * A check for a feature that became the default in, say, 8.2 only reports when the project targets 8.2 or newer
 * (parameter `woocommerce.targetVersion`, defaulting to the newest supported release).
 */
final readonly class FeatureGate
{
    public const LATEST = '11.3';

    public function __construct(
        private string $targetVersion = self::LATEST
    ) {}

    public function isActive(string $defaultSince): bool
    {
        return version_compare($this->targetVersion, $defaultSince, '>=');
    }
}
