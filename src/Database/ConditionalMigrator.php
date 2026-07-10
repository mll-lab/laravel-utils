<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Migrations\Migrator;

class ConditionalMigrator extends Migrator
{
    protected function runUp($file, $batch, $pretend): void
    {
        $migration = $this->resolvePath($file);

        $name = $this->getMigrationName($file);

        if ($migration instanceof ConditionalMigration && ! $migration->shouldRun()) {
            if (! $pretend) {
                $this->write(Info::class, "Skipped migrating {$name}, shouldRun() returned false");
            }

            return;
        }

        if ($pretend) {
            $this->pretendToRun($migration, 'up');

            return;
        }

        // @phpstan-ignore-next-line $this->write() falsely claims to not accept callable
        $this->write(Task::class, $name, fn () => $this->runMigration($migration, 'up'));

        $this->repository->log($name, $batch);
    }
}
