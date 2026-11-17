<?php declare(strict_types=1);

use App\Models\User;

User::factory()->createOne();
User::factory()->createOne([]);
User::factory()->createOne([
    'name' => 'John Doe',
]);

User::factory();
User::factory()->count(3);
User::factory(3);
User::factory(null, ['name' => 'John Doe']);
User::factory(state: ['name' => 'John Doe']);
User::factory(['name' => 'John Doe']);
