<?php declare(strict_types=1);

namespace App\Database;

use MLL\LaravelUtils\Database\Autoincrement;

final class MaxNo extends Autoincrement
{
    public static function name(): string
    {
        return 'max_no';
    }
}
