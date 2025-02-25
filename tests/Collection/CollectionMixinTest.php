<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Collection;

use Illuminate\Support\Collection;
use MLL\LaravelUtils\Tests\TestCase;

final class CollectionMixinTest extends TestCase
{
    public const CONNECT_MULTIPLES = ', ';
    public const CONNECT_LAST = ' and ';

    public function testInterpose(): void
    {
        self::assertSame([1, self::CONNECT_MULTIPLES, 2, self::CONNECT_MULTIPLES, 3], (new Collection([1, 2, 3]))->interpose(self::CONNECT_MULTIPLES)->all());
    }

    public function testNaturalAndReturnsItemItselfIfOnlyOneIsGiven(): void
    {
        self::assertSame([1], (new Collection([1]))->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }

    public function testNaturalAndReturnsEmptyCollection(): void
    {
        self::assertSame([], (new Collection())->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }

    public function testNaturalAndConnectsExactlyTwo(): void
    {
        self::assertSame([1, self::CONNECT_LAST, 2], (new Collection([1, 2]))->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }

    public function testNaturalAndConnectsExactlyThree(): void
    {
        self::assertSame([1, self::CONNECT_MULTIPLES, 2, self::CONNECT_LAST, 3], (new Collection([1, 2, 3]))->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }

    public function testNaturalAndConnectsMultiples(): void
    {
        self::assertSame([1, self::CONNECT_MULTIPLES, 2, self::CONNECT_MULTIPLES, 3, self::CONNECT_MULTIPLES, 4, self::CONNECT_LAST, 5], (new Collection([1, 2, 3, 4, 5]))->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }

    public function testNaturalAndHandlesReindexing(): void
    {
        // Cause the input collection to miss an index
        $items = (new Collection([1, 2, 3]))
            ->reject(fn (int $item): bool => $item === 2);

        self::assertSame([1, self::CONNECT_LAST, 3], $items->interpose(self::CONNECT_MULTIPLES, self::CONNECT_LAST)->all());
    }
}
