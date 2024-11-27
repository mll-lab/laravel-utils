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
    /** @phpstan-ignore missingType.generics (declaring type not specified here on purpose because it is an interface) */
    public function stateManager(): MorphOne;

    /** @return class-string<State> */
    public function stateClass(): string;

    public function stateMachine(): StateMachine;
}
