<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @property-read HasStateManagerInterface&Model $stateable
 *
 * Virtual
 * @property-read SupportCollection<State> $canTransitionTo
 */
trait IsStateManager
{
    /** @return MorphTo<HasStateManagerInterface&Model, self> */
    abstract public function stateable(): MorphTo;

    /** @return SupportCollection<class-string<State>, State> */
    public function getCanTransitionToAttribute(): SupportCollection
    {
        $stateable = $this->stateable;
        $stateable->refresh();

        return $stateable->stateClass()::config()
            ->possibleNextStates($stateable->state);
    }

    /** @return SupportCollection<array-key, Transition> */
    public function possibleTransitions(): SupportCollection
    {
        $stateable = $this->stateable;

        return $stateable->stateClass()::config()
            ->possibleNextTransitions($stateable);
    }
}
