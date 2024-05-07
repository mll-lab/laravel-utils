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
    public function get($model, $key, $value, $attributes): ?int
    {
        return $this->cast($value);
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed>  $attributes
     */
    public function set($model, $key, $value, $attributes): ?int
    {
        return $this->cast($value);
    }

    protected function cast(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_float($value)) {
            $value = (int) round($value);
        }

        if (! is_int($value)) {
            $type = gettype($value);
            $valueString = var_export($value, true);
            throw new \RuntimeException("Expected int, got {$type}: {$valueString}.");
        }

        return max(0, $value);
    }
}
