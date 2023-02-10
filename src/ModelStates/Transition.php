<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;

abstract class Transition
{
    /**
     * @param class-string<State> $from
     * @param class-string<State> $to
     */
    final public function __construct(
        protected HasStateManagerInterface&Model $model,
        protected string $from,
        protected string $to
    ) {
    }

    public function canTransition(): bool
    {
        return true;
    }

    /**
     * Can be reused in default state transitions.
     */
    final protected function manage(): void
    {
        $this->model->stateManager->setAttribute(ModelStatesServiceProvider::stateColumnName(), $this->to::name());
        $this->model->stateManager->save();
    }
}
