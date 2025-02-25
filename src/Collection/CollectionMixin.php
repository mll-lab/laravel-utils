<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Collection;

use Illuminate\Support\Collection;

/**
 * Add macros to Laravel's collection class.
 *
 * @mixin Collection<array-key, mixed>
 */
final class CollectionMixin
{
    /**
     * Connect one or more items with a given connector.
     *
     * E.g. interpose(['foo', 'bar', 'baz'], ', ') -> ['foo', ', ', 'bar', ', ', 'baz']
     *
     * You may pass a different connector to use for the last one.
     *
     * E.g. interpose(['foo', 'bar', 'baz'], ', ', ' and ') -> ['foo', ', ', 'bar', ' and ', 'baz']
     */
    public function interpose(): \Closure
    {
        return function (mixed $connectMultiples, mixed $connectLast = null): Collection {
            $itemCount = $this->count();

            return $this
                ->values() // Reindex starting from 0, as we rely on the index
                ->flatMap(fn (mixed $item, int $index): array => match ($itemCount - $index - 1) { // How many items are left in the collection?
                    0 => [$item],
                    1 => [$item, $connectLast ?? $connectMultiples],
                    default => [$item, $connectMultiples],
                });
        };
    }
}
