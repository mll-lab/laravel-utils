<?php declare(strict_types=1);

use Illuminate\Support\Collection;
use MLL\LaravelUtils\Collection\CollectionUtils;

use function PHPStan\Testing\assertType;

$nested = new Collection([
    new Collection([1, 2, 3]),
    new Collection([4, 5, 6]),
]);
$flattened = CollectionUtils::flattenOnce($nested);
assertType('Illuminate\Support\Collection<int, int>', $flattened);
