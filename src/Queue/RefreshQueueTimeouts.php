<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Queue;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RefreshQueueTimeouts extends Command
{
    protected $signature = '
        laravel-utils:refresh-queue-timeouts
        {--queue=* : Limit to specific queue(s)}
        {--dry-run : Show what would change without modifying anything}
    ';

    protected $description = 'Refresh stale timeout values in queued and failed jobs from current class defaults';

    public function handle(): void
    {
        $jobsTable = config('queue.connections.database.table', 'jobs');
        assert(is_string($jobsTable));

        $failedJobsTable = config('queue.failed.table', 'failed_jobs');
        assert(is_string($failedJobsTable));

        /** @var bool $isDryRun flag option, see signature */
        $isDryRun = $this->option('dry-run');

        /** @var list<string> $queues option may be used multiple times, see signature */
        $queues = $this->option('queue');

        $timeoutsByClass = $this->resolveTimeouts($jobsTable, $failedJobsTable);

        if ($timeoutsByClass === []) {
            $this->info('No job classes with timeout properties found.');

            return;
        }

        $totalUpdated = 0;

        foreach ($timeoutsByClass as $className => $timeout) {
            foreach ([$jobsTable, $failedJobsTable] as $table) {
                $updated = $this->updateTable($table, $className, $timeout, $queues, $isDryRun);
                if ($updated === 0) {
                    continue;
                }

                $totalUpdated += $updated;

                $action = $isDryRun
                    ? 'Would update'
                    : 'Updated';
                $this->line("{$action} {$updated} job(s) in <comment>{$table}</comment> for <info>{$className}</info> to timeout {$timeout}.");
            }
        }

        if ($totalUpdated === 0) {
            $this->info('All jobs already have current timeout values.');
        } elseif ($isDryRun) {
            $this->warn("Dry run: {$totalUpdated} job(s) would be updated.");
        } else {
            $this->info("Updated {$totalUpdated} job(s).");
        }
    }

    /** @return array<string, int> */
    private function resolveTimeouts(string $jobsTable, string $failedJobsTable): array
    {
        $classNames = (new Collection([$jobsTable, $failedJobsTable]))
            ->flatMap(fn (string $table): Collection => DB::table($table)
                ->select(DB::raw("DISTINCT JSON_UNQUOTE(JSON_EXTRACT(payload, '$.displayName')) as display_name"))
                ->pluck('display_name'))
            ->unique()
            ->filter();

        $timeoutsByClass = [];

        foreach ($classNames as $className) {
            assert(is_string($className));

            if (! class_exists($className)) {
                $this->warn("Skipping unknown class: {$className}.");

                continue;
            }

            $reflection = new \ReflectionClass($className);

            if (! $reflection->hasProperty('timeout')) {
                continue;
            }

            $property = $reflection->getProperty('timeout');
            $defaultValue = $property->getDefaultValue();

            if (! is_int($defaultValue)) {
                continue;
            }

            $timeoutsByClass[$className] = $defaultValue;
        }

        return $timeoutsByClass;
    }

    /** @param list<string> $queues */
    private function updateTable(string $table, string $className, int $timeout, array $queues, bool $isDryRun): int
    {
        $query = DB::table($table)
            ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payload, '$.displayName'))"), $className)
            ->where(DB::raw("JSON_EXTRACT(payload, '$.timeout')"), '!=', $timeout);

        if ($queues !== []) {
            $query->whereIn('queue', $queues);
        }

        if ($isDryRun) {
            return $query->count();
        }

        return $query->update([
            'payload' => DB::raw("JSON_SET(payload, '$.timeout', {$timeout})"),
        ]);
    }
}
