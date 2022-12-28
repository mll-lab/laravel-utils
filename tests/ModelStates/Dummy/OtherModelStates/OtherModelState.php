<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\OtherModelStates;

use MLL\LaravelUtils\ModelStates\State;
use MLL\LaravelUtils\ModelStates\StateConfig;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\Transitions\CustomInvalidTransition;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\Transitions\CustomTransition;

abstract class OtherModelState extends State
{
    public static function config(): StateConfig
    {
        return (new StateConfig())
            ->allowTransition(StateX::class, StateY::class, CustomTransition::class)
            ->allowTransition(StateX::class, StateZ::class, CustomInvalidTransition::class);
    }

    public static function defaultState(): OtherModelState
    {
        return new StateX();
    }
}
