<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/**
 * Do not use this class directly, extend RobustQueueableMailable instead.
 *
 * Its only purpose is to include the trait Queueable here to avoid a conflict between:
 * - @see Queueable::$queue
 * - @see RobustQueueableMailable::$queue
 *
 * @internal
 */
abstract class QueueableMailable extends Mailable
{
    use Queueable;
}
