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
        {--mailer= : See config/mail.php mailers}
    ';

    public function handle(): void
    {
        $from = $this->option('from');
        if (! is_string($from)) {
            $fromType = gettype($from);
            throw new \UnexpectedValueException("Expected option --from to be string, got {$fromType}.");
        }

        $to = $this->option('to');
        if (! is_string($to)) {
            $toType = gettype($to);
            throw new \UnexpectedValueException("Expected option --to to be string, got {$toType}.");
        }

        $mail = (new TestMail())
            ->from($from)
            ->to($to);

        $mailer = $this->option('mailer');
        if (! is_null($mailer) && ! is_string($mailer)) { // @phpstan-ignore-line
            $mailerType = gettype($mailer);
            throw new \UnexpectedValueException("Expected option --mailer to be string or null, got {$mailerType}.");
        }

        Mail::mailer($mailer)
            ->send($mail);
    }
}
