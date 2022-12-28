<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

use MLL\LaravelUtils\ModelStates\Transition;

class InvalidConfig extends ModelStateException
{
    public static function doesNotExtendTransition(string $class): InvalidConfig
    {
        return self::doesNotExtendBaseClass($class, Transition::class);
    }

    public static function doesNotExtendBaseClass(string $class, string $baseClass): InvalidConfig
    {
        return ClassDoesNotExtendBaseClass::make($class, $baseClass);
    }
}
