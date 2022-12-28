<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates;

final class StateB extends ModelState
{
    public static function name(): string
    {
        return 'FOO_BAR';
    }
}
