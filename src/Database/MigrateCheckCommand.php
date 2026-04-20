<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class MigrateCheckCommand extends BaseCommand
{
    protected $name = 'migrate:check';

    protected $description = 'Check if there are any pending migrations';

    public function handle(): int
    {
        $migrator = $this->migrator();

        /** @var int Callback always returns int */
        return $migrator->usingConnection($this->option('database'), function () use ($migrator): int { // @phpstan-ignore argument.type
            if (! $migrator->repositoryExists()) {
                $this->components->error('Migration table not found.');

                return 1;
            }

            $files = $migrator->getMigrationFiles($this->getMigrationPaths());
            $ran = $migrator->getRepository()->getRan();

            $pendingFiles = array_diff_key($files, array_flip($ran)); // @phpstan-ignore argument.type

            $trulyPending = [];
            foreach ($pendingFiles as $name => $path) {
                $migration = $this->resolveMigration($migrator, $path);

                if ($migration instanceof ConditionalMigration && ! $migration->shouldRun()) {
                    continue;
                }

                $trulyPending[] = $name;
            }

            if ($trulyPending === []) {
                $this->components->info('No pending migrations');

                return 0;
            }

            $this->components->error('Pending migrations');
            $this->newLine();

            foreach ($trulyPending as $name) {
                $this->components->twoColumnDetail($name, '<fg=yellow;options=bold>Pending</>');
            }

            $this->newLine();

            return 1;
        });
    }

    private function migrator(): Migrator
    {
        return $this->laravel->make('migrator');
    }

    private function resolveMigration(Migrator $migrator, string $path): object
    {
        $migration = require $path;

        if (is_object($migration)) {
            return $migration;
        }

        return $migrator->resolve(
            $migrator->getMigrationName($path),
        );
    }

    protected function getOptions(): array // @phpstan-ignore missingType.iterableValue
    {
        return [ // @phpstan-ignore return.type
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
            ['path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path(s) to the migrations files to use'],
            ['realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths'],
        ];
    }
}
