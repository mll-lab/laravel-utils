<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Casts;

use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\Casts\CoordinatesCast;
use MLL\LaravelUtils\Casts\UnsignedInt;
use MLL\Utils\Microplate\Coordinates;
use MLL\Utils\Microplate\CoordinateSystem12x8;

/**
 * @property Coordinates<CoordinateSystem12x8>|null $coordinates
 * @property int|null $unsigned_int
 */
final class CastsModel extends Model
{
    protected $casts = [
        'coordinates' => CoordinatesCast::class . ':' . CoordinateSystem12x8::class,
        'unsigned_int' => UnsignedInt::class,
    ];

    /** Get a raw attribute value, ignoring casts and accessors. */
    public function getRawAttribute(string $key): float|bool|int|string|null
    {
        $value = $this->attributes[$key];
        assert(is_scalar($value) || is_null($value));

        return $value;
    }

    /** Set a raw attribute value, ignoring casts and accessors. */
    public function setRawAttribute(string $key, float|bool|int|string|null $value): void
    {
        $this->attributes[$key] = $value;
        unset($this->classCastCache[$key]);
    }
}
