<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property State $state
 * @property-read Model $stateManager
 */
interface HasStateManagerInterface
{
    public function stateManager(): MorphOne;

    /**
     * @return class-string<State>
     */
    public function stateClass(): string;

    public function stateMachine(): StateMachine;
}
