<?php declare(strict_types=1);

namespace App\ModelStates\OtherModelStates;

use App\ModelStates\StateManager;
use App\ModelStates\Transitions\CustomInvalidTransition;
use App\ModelStates\Transitions\CustomTransition;
use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\ModelStates\State;
use MLL\LaravelUtils\ModelStates\StateConfig;

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

    /** @return class-string<Model> */
    public static function stateManagerClass(): string
    {
        return StateManager::class;
    }
}
