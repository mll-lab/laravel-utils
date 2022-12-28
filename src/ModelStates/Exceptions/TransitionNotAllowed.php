<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

final class TransitionNotAllowed extends CouldNotPerformTransition
{
    protected string $transitionClass;

    public static function make(string $modelClass, string $transitionClass): self
    {
        return new self("The transition `{$transitionClass}` is not allowed on model `{$modelClass}` at the moment.");
    }
}
