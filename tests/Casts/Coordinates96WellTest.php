<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Casts;

use MLL\LaravelUtils\Tests\TestCase;
use MLL\Utils\Microplate\Coordinates;
use MLL\Utils\Microplate\CoordinateSystem96Well;

final class Coordinates96WellTest extends TestCase
{
    public function testCast(): void
    {
        $model = new CastsModel();
        self::assertNull($model->coordinates);

        $cast = new Coordinates('A', 2, new CoordinateSystem96Well());
        $raw = 'A2';

        $model->coordinates = $cast;
        self::assertSame($raw, $model->getRawAttribute('coordinates'));

        $model->setRawAttribute('coordinates', $raw);
        self::assertEquals($cast, $model->coordinates);
    }
}
