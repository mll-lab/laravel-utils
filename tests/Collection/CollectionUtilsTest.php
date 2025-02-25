<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Collection;

use Illuminate\Support\Collection;
use MLL\LaravelUtils\Collection\CollectionUtils;
use MLL\LaravelUtils\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class CollectionUtilsTest extends TestCase
{
    /**
     * @param Collection<array-key, mixed> $nested
     * @param Collection<array-key, mixed> $flat
     */
    #[DataProvider('nestedToFlat')]
    public function testFlattenOnce(Collection $nested, Collection $flat): void
    {
        self::assertEquals($flat, CollectionUtils::flattenOnce($nested));
    }

    /**
     * @return iterable<
     *     array{
     *         0: Collection<array-key, *>,
     *         1: Collection<array-key, *>,
     *     }
     * >
     */
    public static function nestedToFlat(): iterable
    {
        yield [
            new Collection([
                new Collection([1, 2, 3]),
                new Collection([4, 5, 6]),
            ]),
            new Collection([1, 2, 3, 4, 5, 6]),
        ];

        yield [
            new Collection([1, 2, 3]),
            new Collection([1, 2, 3]),
        ];

        yield [
            new Collection([]),
            new Collection([]),
        ];
    }
}
