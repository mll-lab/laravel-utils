<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;

abstract class Transition
{
    protected HasStateManagerInterface&Model $model;

    /**
     * @var class-string<State>
     */
    protected string $from;

    /**
     * @var class-string<State>
     */
    protected string $to;

    /**
     * @param class-string<State> $from
     * @param class-string<State> $to
     */
    final public function __construct(HasStateManagerInterface&Model $model, string $from, string $to)
    {
        $this->model = $model;
        $this->from = $from;
        $this->to = $to;
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
