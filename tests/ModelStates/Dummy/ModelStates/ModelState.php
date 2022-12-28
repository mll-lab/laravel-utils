<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates;

use MLL\LaravelUtils\ModelStates\State;
use MLL\LaravelUtils\ModelStates\StateConfig;

abstract class ModelState extends State
{
    public static function config(): StateConfig
    {
        return (new StateConfig())
            ->allowTransition(StateA::class, StateB::class)
            ->allowTransition([StateA::class, StateB::class], StateC::class)
            ->allowTransition(StateA::class, StateD::class);
    }

    public static function defaultState(): ModelState
    {
        return new StateA();
    }
}
