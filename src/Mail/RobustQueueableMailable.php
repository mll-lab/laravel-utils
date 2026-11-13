<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

/**
 * Sets reasonable defaults for mailables to enable robust queuing.
 *
 * Retries for a day, so mails survive a mail server outage of several hours.
 */
abstract class RobustQueueableMailable extends QueueableMailable
{
    use SerializesModels;

    /**
     * Seconds between attempts, the last value repeats.
     *
     * @var list<int>
     */
    public array $backoff = [60, 300, 900, 1800];

    /**
     * Run a queue worker for it, e.g. `php artisan queue:work --queue=mail`.
     *
     * @var string|null
     */
    public $queue = 'mail';

    /** https://laravel.com/docs/queues#time-based-attempts */
    public function retryUntil(): Carbon
    {
        return Carbon::now()->addDay();
    }
}
