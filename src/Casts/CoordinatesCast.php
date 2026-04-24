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
class CoordinatesCast implements CastsAttributes
{
    public function __construct(
        /** @var class-string<TCoordinateSystem> */
        protected string $coordinateSystemClass,
    ) {}

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<mixed> $attributes
     *
     * @return Coordinates<TCoordinateSystem>|null
     */
    public function get($model, $key, $value, $attributes): ?Coordinates
    {
        if (! is_string($value)) {
            return null;
        }

        return Coordinates::fromString($value, new $this->coordinateSystemClass());
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<mixed>  $attributes
     */
    public function set($model, $key, $value, $attributes): string
    {
        assert($value instanceof Coordinates);

        return $value->toString();
    }
}
