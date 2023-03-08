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
    ) {}

    public function canTransition(): bool
    {
        return true;
    }

    public function direction(): TransitionDirection
    {
        return TransitionDirection::FORWARD;
    }

    /**
     * Determines whether this transition should be visible from the AdminFrontend.
     * Useful if a transition should be triggered by a backend process and not by a user.
     */
    public function isVisibleFromAdminFrontend(): bool
    {
        return true;
    }

    public function to(): State
    {
        return new $this->to();
    }

    public function from(): State
    {
        return new $this->from();
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
