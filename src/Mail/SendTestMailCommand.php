<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMailCommand extends Command
{
    protected $signature = '
        send-test-mail
        {--from= : Sender}
        {--to= : Recipient}
        {--reply-to= : Reply-To}
        {--mailer= : See config/mail.php mailers}
    ';

    public function handle(): void
    {
        $mail = new TestMail();

        $from = $this->option('from');
        if (! is_null($from)) {
            if (! is_string($from)) { // @phpstan-ignore-line option values can also be arrays
                $fromType = gettype($from);
                throw new \UnexpectedValueException("Expected option --from to be string, got {$fromType}.");
            }
            $mail->from($from);
        }

        $to = $this->option('to');
        if (! is_string($to)) {
            $toType = gettype($to);
            throw new \UnexpectedValueException("Expected option --to to be string, got {$toType}.");
        }
        $mail->to($to);

        $replyTo = $this->option('reply-to');
        if (! is_null($replyTo)) {
            if (! is_string($replyTo)) { // @phpstan-ignore-line option values can also be arrays
                $replyToType = gettype($replyTo);
                throw new \UnexpectedValueException("Expected option --reply-to to be string, got {$replyToType}.");
            }
            $mail->replyTo($replyTo);
        }

        $mailer = $this->option('mailer');
        if (! is_null($mailer) && ! is_string($mailer)) { // @phpstan-ignore-line option values can also be arrays
            $mailerType = gettype($mailer);
            throw new \UnexpectedValueException("Expected option --mailer to be string or null, got {$mailerType}.");
        }

        Mail::mailer($mailer)
            ->send($mail);
    }
}
