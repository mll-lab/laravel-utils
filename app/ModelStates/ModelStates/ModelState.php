<?php declare(strict_types=1);

namespace App\ModelStates\ModelStates;

use App\ModelStates\StateManager;
use App\ModelStates\Transitions\TransitionWithException;
use App\ModelStates\Transitions\CustomInvalidTransition;
use MLL\LaravelUtils\ModelStates\State;
use MLL\LaravelUtils\ModelStates\StateConfig;

abstract class ModelState extends State
{
    public static function config(): StateConfig
    {
        return (new StateConfig())
            ->allowTransition(StateA::class, StateB::class)
            ->allowTransition(StateA::class, StateA::class, TransitionWithException::class)
            ->allowTransition([StateA::class, StateB::class], StateC::class)
            ->allowTransition(StateA::class, StateD::class)
            ->allowTransition(StateC::class, StateA::class)
            ->allowTransition(StateD::class, StateA::class, CustomInvalidTransition::class);
    }

    public static function defaultState(): ModelState
    {
        return new StateA();
    }

    public static function stateManagerClass(): string
    {
        return StateManager::class;
    }
}
