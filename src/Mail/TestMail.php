<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    /** @return $this */
    public function build(): self
    {
        return $this->subject('This is a test mail sent by php artisan send-test-mail')
            ->html(/** @lang HTML */ '<p>This is a test mail sent by php artisan send-test-mail</p>');
    }
}
