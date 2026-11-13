<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Mail;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use MLL\LaravelUtils\Mail\RobustQueueableMailable;
use MLL\LaravelUtils\Tests\TestCase;

final class RobustQueueableMailableTest extends TestCase
{
    public function testQueuesOnMailQueueRetryingForADay(): void
    {
        Carbon::setTestNow(Carbon::createStrict(2026, 11, 13, 10, 0, 0));
        $mailFake = Mail::fake();

        Mail::to('foo@bar.baz')->queue(new class extends RobustQueueableMailable {
            public function build(): self
            {
                return $this->html('foo');
            }
        });

        $queued = $mailFake->queued(RobustQueueableMailable::class);
        self::assertCount(1, $queued);

        $mail = $queued->firstOrFail();
        self::assertInstanceOf(RobustQueueableMailable::class, $mail);
        self::assertSame('mail', $mail->queue);
        self::assertSame([60, 300, 900, 1800], $mail->backoff);
        self::assertSame(
            Carbon::createStrict(2026, 11, 14, 10, 0, 0)->getTimestamp(),
            $mail->retryUntil()->getTimestamp(),
        );
    }
}
