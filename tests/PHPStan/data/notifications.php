<?php declare(strict_types=1);

use Illuminate\Notifications\Notifiable;

final class NotifiableUser
{
    use Notifiable;
}

Illuminate\Support\Facades\Notification::fake();
