<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withImportNames()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        // PHPStan rule fixtures: their code is the input under test.
        __DIR__ . '/tests/PHPStan/data',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        naming: true
    )
    ->withPhpVersion(PhpVersion::PHP_82);
