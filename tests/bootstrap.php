<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// PHPStan's and Rector's test harnesses ignore scanFiles, so the tests load the stubs at runtime instead.
require __DIR__ . '/../vendor/php-stubs/wordpress-stubs/wordpress-stubs.php';
require __DIR__ . '/../vendor/php-stubs/woocommerce-stubs/woocommerce-stubs.php';
