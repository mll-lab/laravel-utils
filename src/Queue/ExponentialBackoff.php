<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Queue;

/**
 * Sane defaults for handling unexpected exceptions in jobs.
 *
 * - frees worker resources from jobs that are likely to never succeed
 * - prevents flooding Sentry with an ever-repeating error report
 * - allows for graceful recovery in case the error is temporary
 *
 * https://cloud.google.com/iot/docs/how-tos/exponential-backoff
 */
trait ExponentialBackoff
{
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * https://laravel.com/docs/queues#max-exceptions
     */
    public int $maxExceptions = 10;

    /**
     * The amount of seconds to wait before retrying the job.
     *
     * We go up to a maximum interval of 64 seconds to ensure the waiting
     * time between attempts is not too long.
     *
     * Combined with $maxExceptions, the duration adds up to just over
     * 5 minutes total, after which the job fails.
     *
     * https://laravel.com/docs/queues#dealing-with-failed-jobs
     */
    public array $backoff = [1, 2, 4, 8, 16, 32, 64];
}
