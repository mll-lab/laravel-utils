<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Casts;

use MLL\Utils\Microplate\CoordinateSystem;
use MLL\Utils\Microplate\CoordinateSystem96Well;

/** @extends CoordinatesCast<CoordinateSystem96Well> */
class Coordinates96Well extends CoordinatesCast
{
    protected function coordinateSystem(): CoordinateSystem
    {
        return new CoordinateSystem96Well();
    }
}
