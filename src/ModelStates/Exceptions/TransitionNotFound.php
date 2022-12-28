<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;

final class TransitionNotFound extends CouldNotPerformTransition
{
    public function __construct(string $from, string $to, HasStateManagerInterface $model)
    {
        $modelClass = get_class($model);
        parent::__construct("Transitions from `{$from}` to `{$to}` on model `{$modelClass}` was not found, did you forget to register it in the config?");
    }
}
