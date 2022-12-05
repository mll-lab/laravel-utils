<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Database;

use MLL\LaravelUtils\Tests\DBTestCase;

final class AutoincrementTest extends DBTestCase
{
    public function testIncrementsIDs(): void
    {
        $this->assertAutoincrementRowCount(0);

        self::assertSame(1, MaxNo::next());
        $this->assertAutoincrementRowCount(1);

        self::assertSame(2, MaxNo::next());
        $this->assertAutoincrementRowCount(1);
    }

    public function testSet(): void
    {
        $this->assertAutoincrementRowCount(0);

        MaxNo::set(3);
        $this->assertAutoincrementRowCount(1);
        self::assertSame(4, MaxNo::next());
        $this->assertAutoincrementRowCount(1);

        MaxNo::set(0);
        $this->assertAutoincrementRowCount(1);
        self::assertSame(1, MaxNo::next());
        $this->assertAutoincrementRowCount(1);

        MaxNo::set(1);
        MaxNo::set(1);
        $this->assertAutoincrementRowCount(1);
        self::assertSame(2, MaxNo::next());
        $this->assertAutoincrementRowCount(1);
    }

    private function assertAutoincrementRowCount(int $count): void
    {
        $this->assertDatabaseCount(MaxNo::name(), $count);
    }
}
