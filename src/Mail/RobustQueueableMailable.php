<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Illuminate\Queue\SerializesModels;
use MLL\LaravelUtils\Queue\ExponentialBackoff;

/**
 * Sets reasonable defaults for mailables to enable robust queuing.
 */
abstract class RobustQueueableMailable extends QueueableMailable
{
    use ExponentialBackoff;
    use SerializesModels;

    /** https://laravel.com/docs/queues#max-attempts */
    public int $tries = 10;

    /**
     * Run a queue worker for it, e.g. `php artisan queue:work --queue=mail`.
     *
     * @var string|null
     */
    public $queue = 'mail';
}
