<?php declare(strict_types=1);

use Illuminate\Support\Collection;
use MLL\LaravelUtils\Collection\CollectionUtils;

use function PHPStan\Testing\assertType;

/** @var Collection<int, array<int>> $nested */
$flattened = CollectionUtils::flattenOnce($nested);
assertType('Illuminate\Support\Collection<int, int>', $flattened);
