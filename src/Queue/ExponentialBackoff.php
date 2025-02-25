<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Queue;

/**
 * Sane values for exponentially rising backoff times for failed jobs.
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
     * The amount of seconds to wait before retrying the job.
     *
     * We go up to a maximum interval of 64 seconds to ensure the waiting
     * time between attempts is not too long.
     *
     * The duration for 10 tries adds up to just over 5 minutes total,
     * after which the job fails.
     *
     * https://laravel.com/docs/queues#dealing-with-failed-jobs
     *
     * @var list<int>
     */
    public array $backoff = [1, 2, 4, 8, 16, 32, 64];
}
