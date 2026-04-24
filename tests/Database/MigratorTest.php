<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Database;

use Illuminate\Support\Facades\DB;
use MLL\LaravelUtils\Database\ConditionalMigrator;
use MLL\LaravelUtils\Tests\DBTestCase;
use Symfony\Component\Console\Output\BufferedOutput;

use function Safe\realpath;

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

    public function testPretendSkipsMigrationThatShouldNotRun(): void
    {
        $migrator = $this->app->make('migrator');
        self::assertInstanceOf(ConditionalMigrator::class, $migrator);

        $output = new BufferedOutput();
        $migrator->setOutput($output);

        $path = realpath(__DIR__ . '/../../app/migrations');
        $migrator->run([$path], ['pretend' => true]);

        $pretendOutput = $output->fetch();
        self::assertStringNotContainsString('conditional_false', $pretendOutput);
        self::assertStringContainsString('unconditional', $pretendOutput);
    }
}
