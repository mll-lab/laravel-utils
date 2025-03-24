<?php declare(strict_types=1);

use App\Models\User;

User::create();
User::create([]);
User::create([
    'name' => 'John Doe',
]);
