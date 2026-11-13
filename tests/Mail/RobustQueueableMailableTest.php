<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Mail;

use Illuminate\Support\Facades\Mail;
use MLL\LaravelUtils\Mail\RobustQueueableMailable;
use MLL\LaravelUtils\Tests\TestCase;

final class RobustQueueableMailableTest extends TestCase
{
    public function testQueuesOnMailQueueWithTenTries(): void
    {
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
        self::assertSame(10, $mail->tries);
    }
}
