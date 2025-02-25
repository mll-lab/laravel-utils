<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

/**
 * @property-read HasStateManagerInterface&Model $stateable
 *
 * Virtual
 * @property-read Collection<array-key, State> $canTransitionTo
 */
trait IsStateManager
{
    /** @return MorphTo<HasStateManagerInterface&Model, $this> */
    abstract public function stateable(): MorphTo;

    abstract public static function stateColumnName(): string;

    /** @return Collection<class-string<State>, State> */
    public function getCanTransitionToAttribute(): Collection
    {
        $stateable = $this->stateable;
        $stateable->refresh();

        $state = $stateable->state;
        assert($state instanceof State);

        return $stateable->stateClass()::config()
            ->possibleNextStates($state);
    }

    /** @return Collection<array-key, Transition> */
    public function possibleTransitions(): Collection
    {
        $stateable = $this->stateable;

        return $stateable->stateClass()::config()
            ->possibleNextTransitions($stateable);
    }
}
