<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan;

use PHPStan\Rules\Rule;
use PHPUnit\Framework\Assert;

/**
 * @template TRule of Rule
 *
 * @extends \PHPStan\Testing\RuleTestCase<TRule>
 */
abstract class RuleTestCase extends \PHPStan\Testing\RuleTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../extension.neon'];
    }

    /**
     * @param list<string>                   $files
     * @param list<array{0: int, 1: string}> $expected [line, identifier]
     */
    protected function assertFindings(array $files, array $expected): void
    {
        $actual = array_map(
            static fn ($error): array => [(int) $error->getLine(), (string) $error->getIdentifier()],
            $this->gatherAnalyserErrors($files)
        );

        sort($actual);
        sort($expected);

        Assert::assertSame($expected, $actual);
    }
}
