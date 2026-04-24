<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\PendingCommand;
use MLL\LaravelUtils\Tests\DBTestCase;

use function Safe\file_put_contents;
use function Safe\glob;
use function Safe\mkdir;
use function Safe\rmdir;
use function Safe\unlink;

final class MigrateCheckCommandTest extends DBTestCase
{
    public function testExitsWithZeroWhenNoPendingMigrations(): void
    {
        $migrateFresh = $this->artisan('migrate:fresh', [
            '--path' => __DIR__ . '/../../app/migrations',
            '--realpath' => true,
        ]);
        self::assertInstanceOf(PendingCommand::class, $migrateFresh);
        $migrateFresh->assertExitCode(0)->run();

        $migrateCheck = $this->artisan('migrate:check', [
            '--path' => __DIR__ . '/../../app/migrations',
            '--realpath' => true,
        ]);
        self::assertInstanceOf(PendingCommand::class, $migrateCheck);
        $migrateCheck->assertExitCode(0)
            ->expectsOutputToContain('No pending migrations')
            ->run();
    }

    public function testExitsWithOneWhenPendingNonConditionalMigration(): void
    {
        $path = $this->tempMigrationPath();
        $this->createMigrationFile($path, '2099_01_01_000000_create_pending_table.php', <<<'PHP'
            <?php declare(strict_types=1);

            use Illuminate\Database\Migrations\Migration;

            return new class() extends Migration {
                public function up(): void {}
            };
            PHP);

        try {
            $migrateCheck = $this->artisan('migrate:check', [
                '--path' => $path,
                '--realpath' => true,
            ]);
            self::assertInstanceOf(PendingCommand::class, $migrateCheck);
            $migrateCheck->assertExitCode(1)
                ->expectsOutputToContain('2099_01_01_000000_create_pending_table')
                ->run();
        } finally {
            $this->cleanupTempPath($path);
        }
    }

    public function testExitsWithOneWhenPendingConditionalMigrationShouldRun(): void
    {
        $path = $this->tempMigrationPath();
        $this->createMigrationFile($path, '2099_01_01_000000_create_conditional_should_run_table.php', <<<'PHP'
            <?php declare(strict_types=1);

            use Illuminate\Database\Migrations\Migration;
            use MLL\LaravelUtils\Database\ConditionalMigration;

            return new class() extends Migration implements ConditionalMigration {
                public function up(): void {}

                public function shouldRun(): bool
                {
                    return true;
                }
            };
            PHP);

        try {
            $migrateCheck = $this->artisan('migrate:check', [
                '--path' => $path,
                '--realpath' => true,
            ]);
            self::assertInstanceOf(PendingCommand::class, $migrateCheck);
            $migrateCheck->assertExitCode(1)
                ->expectsOutputToContain('2099_01_01_000000_create_conditional_should_run_table')
                ->run();
        } finally {
            $this->cleanupTempPath($path);
        }
    }

    public function testFiltersConditionalMigrationThatShouldNotRun(): void
    {
        $path = $this->tempMigrationPath();
        $this->createMigrationFile($path, '2099_01_01_000000_create_conditional_skip_table.php', <<<'PHP'
            <?php declare(strict_types=1);

            use Illuminate\Database\Migrations\Migration;
            use MLL\LaravelUtils\Database\ConditionalMigration;

            return new class() extends Migration implements ConditionalMigration {
                public function up(): void {}

                public function shouldRun(): bool
                {
                    return false;
                }
            };
            PHP);

        try {
            $migrateCheck = $this->artisan('migrate:check', [
                '--path' => $path,
                '--realpath' => true,
            ]);
            self::assertInstanceOf(PendingCommand::class, $migrateCheck);
            $migrateCheck->assertExitCode(0)
                ->expectsOutputToContain('No pending migrations')
                ->run();
        } finally {
            $this->cleanupTempPath($path);
        }
    }

    public function testMixedPendingShowsOnlyTrulyPending(): void
    {
        $path = $this->tempMigrationPath();
        $this->createMigrationFile($path, '2099_01_01_000000_create_mixed_skip_table.php', <<<'PHP'
            <?php declare(strict_types=1);

            use Illuminate\Database\Migrations\Migration;
            use MLL\LaravelUtils\Database\ConditionalMigration;

            return new class() extends Migration implements ConditionalMigration {
                public function up(): void {}

                public function shouldRun(): bool
                {
                    return false;
                }
            };
            PHP);
        $this->createMigrationFile($path, '2099_01_01_000001_create_mixed_pending_table.php', <<<'PHP'
            <?php declare(strict_types=1);

            use Illuminate\Database\Migrations\Migration;

            return new class() extends Migration {
                public function up(): void {}
            };
            PHP);

        try {
            $migrateCheck = $this->artisan('migrate:check', [
                '--path' => $path,
                '--realpath' => true,
            ]);
            self::assertInstanceOf(PendingCommand::class, $migrateCheck);
            $migrateCheck->assertExitCode(1)
                ->expectsOutputToContain('2099_01_01_000001_create_mixed_pending_table')
                ->run();
        } finally {
            $this->cleanupTempPath($path);
        }
    }

    public function testExitsWithOneWhenMigrationTableNotFound(): void
    {
        Schema::drop('migrations');

        try {
            $migrateCheck = $this->artisan('migrate:check', [
                '--path' => __DIR__ . '/../../app/migrations',
                '--realpath' => true,
            ]);
            self::assertInstanceOf(PendingCommand::class, $migrateCheck);
            $migrateCheck->assertExitCode(1)
                ->expectsOutputToContain('Migration table not found')
                ->run();
        } finally {
            $migrateInstall = $this->artisan('migrate:install');
            self::assertInstanceOf(PendingCommand::class, $migrateInstall);
            $migrateInstall->run();
        }
    }

    private function tempMigrationPath(): string
    {
        $path = __DIR__ . '/../../tmp-test-migrations-' . uniqid();
        mkdir($path, 0777, true);

        return $path;
    }

    private function createMigrationFile(string $path, string $filename, string $content): void
    {
        file_put_contents($path . '/' . $filename, $content);
    }

    private function cleanupTempPath(string $path): void
    {
        foreach (glob($path . '/*') as $file) {
            assert(is_string($file));
            unlink($file);
        }
        rmdir($path);
    }
}
