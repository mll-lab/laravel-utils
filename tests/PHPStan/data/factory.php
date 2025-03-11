<?php declare(strict_types=1);

use App\Models\User;

User::factory()->createOne();
User::factory()->createOne([]);
User::factory()->createOne([
    'name' => 'John Doe',
]);
