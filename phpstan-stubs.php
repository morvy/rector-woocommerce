<?php

declare(strict_types=1);

use Composer\InstalledVersions;

/*
 * Included from extension.neon: adds the bundled WordPress and WooCommerce stubs to PHPStan's scanned files,
 * so order, cart and gateway types resolve. Scanned files are only read, never executed, so they cannot clash
 * with a real WooCommerce install.
 */
$scanFiles = [];

foreach (['php-stubs/wordpress-stubs' => 'wordpress-stubs.php', 'php-stubs/woocommerce-stubs' => 'woocommerce-stubs.php'] as $package => $file) {
    $path = class_exists(InstalledVersions::class) && InstalledVersions::isInstalled($package) ? InstalledVersions::getInstallPath($package) : null;

    if ($path !== null && is_file($path . '/' . $file)) {
        $scanFiles[] = realpath($path . '/' . $file);
    }
}

return ['parameters' => ['scanFiles' => $scanFiles]];
