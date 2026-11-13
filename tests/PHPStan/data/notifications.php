<?php declare(strict_types=1);

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

final class NotifiableUser
{
    use Notifiable;
}

Illuminate\Support\Facades\Notification::fake();
