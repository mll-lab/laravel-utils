<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Mail;

use Illuminate\Support\Facades\Mail;
use MLL\LaravelUtils\Mail\TestMail;
use MLL\LaravelUtils\Tests\TestCase;

final class SendTestMailCommandTest extends TestCase
{
    public function testSendsTestMail(): void
    {
        $mailFake = Mail::fake();

        $from = 'schnack@gogg.ler';
        $to = 'foo@bar.baz';
        $replyTo = 'a@b.c';

        $this->artisan('send-test-mail', [
            '--from' => $from,
            '--to' => $to,
            '--reply-to' => $replyTo,
        ]);

        $sent = $mailFake->sent(TestMail::class);
        self::assertCount(1, $sent);

        $testMail = $sent->firstOrFail();
        assert($testMail instanceof TestMail);
        $testMail->assertFrom($from);
        $testMail->assertTo($to);
        $testMail->assertHasReplyTo($replyTo);
    }
}
