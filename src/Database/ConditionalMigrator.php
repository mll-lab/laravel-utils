<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Migrations\Migrator;

class ConditionalMigrator extends Migrator
{
    protected function runUp($file, $batch, $pretend): void
    {
        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
        $migration = $this->resolvePath($file);

        $name = $this->getMigrationName($file);

        if ($pretend) {
            $this->pretendToRun($migration, 'up');

            return;
        }

        if ($migration instanceof ConditionalMigration && ! $migration->shouldRun()) {
            $this->write(Info::class, "Skipped migrating {$name}");

            return;
        }

        // @phpstan-ignore-next-line $this->write() falsely claims to not accept callable
        $this->write(Task::class, $name, fn () => $this->runMigration($migration, 'up'));

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.
        $this->repository->log($name, $batch);
    }
}
