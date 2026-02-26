<?php declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Collection;

/** @var Collection<int, User> $collection */
/** @var Collection<string, User> $stringKeyedCollection */

// Disallowed: string keys
$collection->keyBy('name');
$collection->sortBy('name');
$collection->sortByDesc('name');
$collection->groupBy('name');
$collection->firstWhere('name', 'John');
$stringKeyedCollection->get('some_key');

// Allowed: closures
$collection->keyBy(fn (User $user): int => $user->getKey());
$collection->sortBy(fn (User $user): int => $user->getKey());
$collection->sortByDesc(fn (User $user): int => $user->getKey());
$collection->groupBy(fn (User $user): int => $user->getKey());
$collection->first(fn (User $user): bool => $user->getKey() === 1);
$collection->get(0);
