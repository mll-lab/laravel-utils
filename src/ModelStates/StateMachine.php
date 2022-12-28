<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Foundation\Application;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotAllowed;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotFound;

final class StateMachine
{
    private StateConfig $stateConfig;

    private HasStateManagerInterface $model;

    private Application $app;

    public function __construct(HasStateManagerInterface $model)
    {
        $this->stateConfig = $model->stateClass()::config();
        $this->model = $model;

        $this->app = app();
    }

    /**
     * @param State|class-string<State> $newState
     */
    public function transitionTo($newState): HasStateManagerInterface
    {
        $from = get_class($this->model->state);
        $to = is_string($newState)
            ? $newState
            : get_class($newState);

        $transitionClass = $this->stateConfig->transition($from, $to);
        if (null === $transitionClass) {
            throw new TransitionNotFound($from, $to, $this->model);
        }

        $transition = new $transitionClass($this->model, $from, $to);

        if (! $transition->canTransition()) {
            throw new TransitionNotAllowed($this->model, $transition);
        }

        if (method_exists($transition, 'handle')) {
            // Allows dependency injection
            $call = $this->app->call([$transition, 'handle']);
            assert($call instanceof HasStateManagerInterface);

            return $call;
        }

        return $this->model;
    }
}
