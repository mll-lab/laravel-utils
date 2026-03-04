<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Queue;

use App\Jobs\TimeoutJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use MLL\LaravelUtils\Tests\DBTestCase;

use function Safe\json_decode;
use function Safe\json_encode;

final class RefreshQueueTimeoutsTest extends DBTestCase
{
    public function testUpdatesStaleTimeoutInJobs(): void
    {
        $this->insertJob('jobs', 'default', TimeoutJob::class, 60);

        $this->runRefreshCommand();

        $payload = $this->jobPayload('jobs');
        self::assertSame(300, $payload['timeout']);
    }

    public function testUpdatesStaleTimeoutInFailedJobs(): void
    {
        $this->insertFailedJob('default', TimeoutJob::class, 60);

        $this->runRefreshCommand();

        $payload = $this->jobPayload('failed_jobs');
        self::assertSame(300, $payload['timeout']);
    }

    public function testSkipsJobsWithCurrentTimeout(): void
    {
        $this->insertJob('jobs', 'default', TimeoutJob::class, 300);

        $command = $this->artisan('laravel-utils:refresh-queue-timeouts');
        self::assertInstanceOf(PendingCommand::class, $command);
        $command
            ->expectsOutputToContain('All jobs already have current timeout values.')
            ->assertSuccessful();

        $payload = $this->jobPayload('jobs');
        self::assertSame(300, $payload['timeout']);
    }

    public function testDryRunDoesNotModifyPayloads(): void
    {
        $this->insertJob('jobs', 'default', TimeoutJob::class, 60);
        $this->insertFailedJob('default', TimeoutJob::class, 60);

        $command = $this->artisan('laravel-utils:refresh-queue-timeouts', ['--dry-run' => true]);
        self::assertInstanceOf(PendingCommand::class, $command);
        $command
            ->expectsOutputToContain('Would update')
            ->assertSuccessful();

        $jobsPayload = $this->jobPayload('jobs');
        self::assertSame(60, $jobsPayload['timeout']);

        $failedPayload = $this->jobPayload('failed_jobs');
        self::assertSame(60, $failedPayload['timeout']);
    }

    public function testQueueFilterOnlyUpdatesMatchingQueue(): void
    {
        $this->insertJob('jobs', 'specific', TimeoutJob::class, 60);
        $this->insertJob('jobs', 'other', TimeoutJob::class, 60);

        $this->runRefreshCommand(['--queue' => ['specific']]);

        $rows = DB::table('jobs')->get();
        self::assertCount(2, $rows);

        foreach ($rows as $row) {
            $payloadString = $row->payload;
            self::assertIsString($payloadString);

            /** @var array<string, mixed> $payload */
            $payload = json_decode($payloadString, true);

            if ($row->queue === 'specific') {
                self::assertSame(300, $payload['timeout']);
            } else {
                self::assertSame(60, $payload['timeout']);
            }
        }
    }

    public function testSkipsUnknownClassGracefully(): void
    {
        $this->insertJob('jobs', 'default', 'App\\Jobs\\NonExistentJob', 60);

        $command = $this->artisan('laravel-utils:refresh-queue-timeouts');
        self::assertInstanceOf(PendingCommand::class, $command);
        $command
            ->expectsOutputToContain('Skipping unknown class')
            ->assertSuccessful();
    }

    /** @param array<string, mixed> $parameters */
    private function runRefreshCommand(array $parameters = []): void
    {
        $command = $this->artisan('laravel-utils:refresh-queue-timeouts', $parameters);
        self::assertInstanceOf(PendingCommand::class, $command);
        $command->assertSuccessful();
    }

    private function insertJob(string $table, string $queue, string $className, int $timeout): void
    {
        DB::table($table)->insert([
            'queue' => $queue,
            'payload' => json_encode([
                'displayName' => $className,
                'timeout' => $timeout,
                'data' => ['command' => ''],
            ]),
            'attempts' => 0,
            'available_at' => time(),
            'created_at' => time(),
        ]);
    }

    private function insertFailedJob(string $queue, string $className, int $timeout): void
    {
        DB::table('failed_jobs')->insert([
            'uuid' => (string) Str::uuid(),
            'connection' => 'database',
            'queue' => $queue,
            'payload' => json_encode([
                'displayName' => $className,
                'timeout' => $timeout,
                'data' => ['command' => ''],
            ]),
            'exception' => 'Test exception',
        ]);
    }

    /** @return array<string, mixed> */
    private function jobPayload(string $table): array
    {
        $row = DB::table($table)->first();
        self::assertInstanceOf(\stdClass::class, $row);

        $payloadString = $row->payload;
        self::assertIsString($payloadString);

        /** @var array<string, mixed> */
        return json_decode($payloadString, true);
    }
}
