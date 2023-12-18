<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Mail;

use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            SendTestMailCommand::class,
        ]);
    }
}
