<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

final class TransitionNotFound extends CouldNotPerformTransition
{
    public static function make(string $from, string $to, string $modelClass): self
    {
        return new self("Transitions from `{$from}` to `{$to}` on model `{$modelClass}` was not found, did you forget to register it in the config?");
    }
}
