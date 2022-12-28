<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\ModelStates\Transition;

class CouldNotPerformTransition extends ModelStateException
{
    public static function notAllowed(HasStateManagerInterface $model, Transition $transitionClass): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        $transitionClass = get_class($transitionClass);

        return TransitionNotAllowed::make($modelClass, $transitionClass);
    }

    public static function notFound(string $from, string $to, HasStateManagerInterface $model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return TransitionNotFound::make($from, $to, $modelClass);
    }
}
