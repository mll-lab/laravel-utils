<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Collection;

use Illuminate\Support\Collection;

class CollectionUtils
{
    /**
     * Map a collection and flatten the result by a single level.
     *
     * @template TValue
     *
     * @param Collection<array-key, TValue> $collection
     *
     * @return Collection<int, (TValue is iterable ? value-of<TValue> : TValue)>
     */
    public static function flattenOnce(Collection $collection): Collection
    {
        /** @var list<(TValue is iterable ? value-of<TValue> : TValue)> $flat */
        $flat = [];

        foreach ($collection as $item) {
            if (! is_iterable($item)) {
                $flat[] = $item;

                continue;
            }

            foreach ($item as $subItem) {
                $flat[] = $subItem;
            }
        }

        return new Collection($flat);
    }
}
