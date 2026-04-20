<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class MigrateCheckCommand extends BaseCommand
{
    protected $name = 'migrate:check';

    protected $description = 'Check if there are any pending migrations';

    public function __construct(
        protected Migrator $migrator
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        /** @var int Callback always returns int */
        return $this->migrator->usingConnection($this->option('database'), function (): int { // @phpstan-ignore argument.type
            if (! $this->migrator->repositoryExists()) {
                $this->components->error('Migration table not found.');

                return 1;
            }

            $files = $this->migrator->getMigrationFiles($this->getMigrationPaths());
            $ran = $this->migrator->getRepository()->getRan();

            $pendingFiles = array_diff_key($files, array_flip($ran));

            $trulyPending = [];
            foreach ($pendingFiles as $name => $path) {
                $migration = $this->resolveMigration($path);

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

    private function resolveMigration(string $path): object
    {
        $migration = require $path;

        if (is_object($migration)) {
            return $migration;
        }

        return $this->migrator->resolve(
            $this->migrator->getMigrationName($path),
        );
    }

    protected function getOptions(): array
    {
        return [ // @phpstan-ignore return.type (matches Laravel convention for option definitions)
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
            ['path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path(s) to the migrations files to use'],
            ['realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths'],
        ];
    }
}
