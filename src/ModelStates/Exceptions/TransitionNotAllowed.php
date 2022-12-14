<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\ModelStates\Transition;

final class TransitionNotAllowed extends CouldNotPerformTransition
{
    public function __construct(HasStateManagerInterface $model, Transition $transitionClass)
    {
        $modelClass = $model::class;
        $transitionClass = $transitionClass::class;
        parent::__construct("The transition `{$transitionClass}` is not allowed on model `{$modelClass}` at the moment.");
    }
}
