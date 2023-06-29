<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Core;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\PendingCommand;
use MLL\LaravelUtils\Tests\TestCase;

final class PublishTest extends TestCase
{
    public function testPublishStubs(): void
    {
        $config = app(ConfigRepository::class);
        $config->set('filesystems.disks.base', [
            'driver' => 'local',
            'root' => $this->app->basePath(),
        ]);

        $base = Storage::disk('base');
        $base->deleteDirectory('database/migrations');
        $base->deleteDirectory('stubs');

        $pendingPublish = $this->artisan('vendor:publish --tag=strict-stubs');
        assert($pendingPublish instanceof PendingCommand);
        $pendingPublish
            ->assertExitCode(0)
            ->run();

        $pendingMakeMigration = $this->artisan('make:migration foo');
        assert($pendingMakeMigration instanceof PendingCommand);
        $pendingMakeMigration
            ->assertExitCode(0)
            ->run();

        $migration = $base->get('stubs/migration.stub');
        self::assertIsString($migration);
        self::assertStringContainsString(': void', $migration);

        $migrations = $base->files('database/migrations');
        self::assertCount(1, $migrations);

        $foo = $base->get($migrations[0]);
        self::assertIsString($foo);
        self::assertStringContainsString(': void', $foo);
    }
}
