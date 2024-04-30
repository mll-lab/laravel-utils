<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Changes incoming or outgoing values that are less than 0 to 0.
 *
 * Not using non-negative-int for typing, as that would make the property harder to use.
 *
 * @implements CastsAttributes<int, int>
 */
class UnsignedInt implements CastsAttributes
{
    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed> $attributes
     */
    public function get($model, $key, $value, $attributes): int
    {
        assert(is_int($value));

        return max(0, $value);
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed>  $attributes
     */
    public function set($model, $key, $value, $attributes): int
    {
        assert(is_int($value));

        return max(0, $value);
    }
}
