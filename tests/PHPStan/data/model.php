<?php declare(strict_types=1);

use App\Models\User;

$user = new User();
$user->update();
$user->update(['name' => 'John Doe']);
