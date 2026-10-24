<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\Rector\Sets\Feature\Hpos;

use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @internal
 */
final class HposTest extends AbstractRectorTestCase
{
    #[DataProvider('provideCases')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideCases(): iterable
    {
        return self::yieldFilesFromDirectory(__DIR__);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/../../../../../config/sets/feature/hpos.php';
    }
}
