<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use MLL\Utils\Microplate\Coordinates;
use MLL\Utils\Microplate\CoordinateSystem;

/**
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @implements CastsAttributes<Coordinates<TCoordinateSystem>, Coordinates<TCoordinateSystem>>
 */
abstract class CoordinatesCast implements CastsAttributes
{
    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed> $attributes
     *
     * @return Coordinates<TCoordinateSystem>
     */
    public function get($model, $key, $value, $attributes): Coordinates
    {
        assert(is_string($value));

        return Coordinates::fromString($value, $this->coordinateSystem());
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

    /** @return TCoordinateSystem */
    abstract protected function coordinateSystem(): CoordinateSystem;
}
