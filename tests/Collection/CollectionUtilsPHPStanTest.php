<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Collection;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class CollectionUtilsPHPStanTest extends TypeInferenceTestCase
{
    /** @return iterable<mixed> */
    public static function dataFileAsserts(): iterable
    {
        // path to a file with actual asserts of expected types:
        yield from self::gatherAssertTypes(__DIR__ . '/data/flattenOnce.php');
    }

    #[DataProvider('dataFileAsserts')]
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }
}
