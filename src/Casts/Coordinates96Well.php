<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use MLL\Utils\Microplate\Coordinates;
use MLL\Utils\Microplate\CoordinateSystem96Well;

/** @implements CastsAttributes<Coordinates<CoordinateSystem96Well>, Coordinates<CoordinateSystem96Well>> */
class Coordinates96Well implements CastsAttributes
{
    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed> $attributes
     *
     * @return Coordinates<CoordinateSystem96Well>
     */
    public function get($model, $key, $value, $attributes): Coordinates
    {
        assert(is_string($value));

        return Coordinates::fromString($value, new CoordinateSystem96Well());
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed>  $attributes
     */
    public function set($model, $key, $value, $attributes): string
    {
        assert($value instanceof Coordinates);

        return $value->toString();
    }
}
