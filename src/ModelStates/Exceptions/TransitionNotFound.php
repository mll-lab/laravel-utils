<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;

final class TransitionNotFound extends CouldNotPerformTransition
{
    public function __construct(HasStateManagerInterface $model, string $from, string $to)
    {
        $modelClass = $model::class;
        $stateClass = $model->stateClass();
        parent::__construct("A transition from `{$from}` to `{$to}` is missing for model `{$modelClass}`. Did you forget to register it in `{$stateClass}::config()`?");
    }
}
