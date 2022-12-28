<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\OtherModelStates;

final class StateX extends OtherModelState
{
    public static function name(): string
    {
        return 'X';
    }
}
