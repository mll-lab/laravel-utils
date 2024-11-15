<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use MLL\LaravelUtils\ModelStates\Exceptions\UnknownStateException;

trait HasStateManager
{
    /** @return class-string<State> */
    abstract public function stateClass(): string;

    public static function bootHasStateManager(): void
    {
        self::created(function (self $self): void {
            if (property_exists($self, 'stateManager') && $self->stateManager !== null) {
                return;
            }

            $stateManagerClass = ModelStatesServiceProvider::stateManagerClass();
            $stateManager = new $stateManagerClass();
            $stateManager->setAttribute(ModelStatesServiceProvider::stateColumnName(), $self->stateClass()::defaultState()::name());

            $self->stateManager()->save($stateManager);
            $self->setRelation('stateManager', $stateManager);
        });
    }

    public function getStateAttribute(): State
    {
        $stateClasses = $this->stateClassConfig()->possibleStates();
        $stateName = $this->stateManager->getAttribute(ModelStatesServiceProvider::stateColumnName());
        assert(is_string($stateName));

        $stateClass = $stateClasses[$stateName] ?? null;
        if ($stateClass === null) {
            throw new UnknownStateException("The state {$stateName} of {$this->table} with id {$this->id} is not part of {$this->stateClass()}.");
        }

        return new $stateClass();
    }

    /** @param State|class-string<State> $newState */
    public function setStateAttribute(State|string $newState): void
    {
        // if the old and new state are the same, do not perform a "transition"
        if ($this->state::name() === $newState::name()) {
            return;
        }

        $this->stateMachine()
            ->transitionTo($newState);
    }

    public function stateManager(): MorphOne
    {
        return $this->morphOne(
            ModelStatesServiceProvider::stateManagerClass(),
            'stateable',
        );
    }

    public function stateClassConfig(): StateConfig
    {
        return $this->stateClass()::config();
    }

    public function stateMachine(): StateMachine
    {
        return new StateMachine($this);
    }
}
