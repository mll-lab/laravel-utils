<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Database;

use MLL\LaravelUtils\Database\Autoincrement;

final class MaxNo extends Autoincrement
{
    public static function name(): string
    {
        return 'max_no';
    }
}
