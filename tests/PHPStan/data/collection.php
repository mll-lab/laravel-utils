<?php declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/** @var Collection<int, User> $collection */
/** @var EloquentCollection<int, User> $eloquentCollection */

// Disallowed: Support\Collection with string keys
$collection->keyBy('name');
$collection->sortBy('name');
$collection->sortByDesc('name');
$collection->groupBy('name');
$collection->firstWhere('name', 'John');
$collection->pluck('name');

// Disallowed: Eloquent\Collection with string keys
$eloquentCollection->keyBy('name');
$eloquentCollection->sortBy('name');
$eloquentCollection->sortByDesc('name');
$eloquentCollection->groupBy('name');
$eloquentCollection->firstWhere('name', 'John');
$eloquentCollection->pluck('name');

// Allowed: Support\Collection with closures
$collection->keyBy(fn (User $user): int => $user->getKey());
$collection->sortBy(fn (User $user): int => $user->getKey());
$collection->sortByDesc(fn (User $user): int => $user->getKey());
$collection->groupBy(fn (User $user): int => $user->getKey());
$collection->first(fn (User $user): bool => $user->getKey() === 1);

// Allowed: sortBy/sortByDesc with arrays (multi-key sorting)
$collection->sortBy([fn (User $userA, User $userB): int => $userA->getKey() <=> $userB->getKey()]);
$collection->sortByDesc([fn (User $userA, User $userB): int => $userA->getKey() <=> $userB->getKey()]);
$eloquentCollection->sortBy([fn (User $userA, User $userB): int => $userA->getKey() <=> $userB->getKey()]);
$eloquentCollection->sortByDesc([fn (User $userA, User $userB): int => $userA->getKey() <=> $userB->getKey()]);

// Allowed: Eloquent\Collection with closures
$eloquentCollection->keyBy(fn (User $user): int => $user->getKey());
$eloquentCollection->sortBy(fn (User $user): int => $user->getKey());
$eloquentCollection->sortByDesc(fn (User $user): int => $user->getKey());
$eloquentCollection->groupBy(fn (User $user): int => $user->getKey());
$eloquentCollection->first(fn (User $user): bool => $user->getKey() === 1);
