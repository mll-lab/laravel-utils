<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class State
{
    abstract public static function config(): StateConfig;

    abstract public static function defaultState(): self;

    /** @return class-string<Model> */
    abstract public static function stateManagerClass(): string;

    public static function name(): string
    {
        $stateClassBaseName = (new \ReflectionClass(get_called_class()))->getShortName();

        return Str::of($stateClassBaseName)
            ->snake()
            ->upper()
            ->__toString();
    }
}
