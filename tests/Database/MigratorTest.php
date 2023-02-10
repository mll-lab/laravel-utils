<?php declare(strict_types=1);

namespace MLL\ConditionalMigrations\Tests;

use Illuminate\Support\Facades\DB;
use MLL\LaravelUtils\Tests\DBTestCase;

final class MigratorTest extends DBTestCase
{
    public function testHandlesRegularMigrationsNormally(): void
    {
        self::assertTrue(DB::getSchemaBuilder()->hasTable('unconditional'));
    }

    public function testRunsMigrationsThatShouldRun(): void
    {
        self::assertTrue(DB::getSchemaBuilder()->hasTable('conditional_true'));
    }

    public function testSkipsMigrationsThatShouldNotRun(): void
    {
        self::assertFalse(DB::getSchemaBuilder()->hasTable('conditional_false'));
    }
}
