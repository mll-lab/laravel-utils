<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use MLL\LaravelUtils\ModelStates\Exceptions\UnknownStateException;

use function Safe\class_uses;

trait HasStateManager
{
    /** @return class-string<State> */
    abstract public function stateClass(): string;

    public static function bootHasStateManager(): void
    {
        self::created(function (self $self): void {
            if (property_exists($self, 'stateManager')
                && isset($self->stateManager)
            ) {
                return;
            }

            $stateClass = $self->stateClass();

            $stateManagerClass = $stateClass::stateManagerClass();
            assert(in_array(needle: IsStateManager::class, haystack: class_uses($stateManagerClass), strict: true));
            $stateManager = new $stateManagerClass();

            assert(method_exists($stateManager, 'stateColumnName'), 'due to IsStateManager');
            $stateColumnName = $stateManager::stateColumnName(); // @phpstan-ignore method.staticCall (due to IsStateManager)
            assert(is_string($stateColumnName), 'due to IsStateManager');

            $defaultState = $stateClass::defaultState();
            $stateManager->setAttribute($stateColumnName, $defaultState::name());

            $self->stateManager()->save($stateManager);
            $self->setRelation('stateManager', $stateManager);
        });
    }

    public function getStateAttribute(): State
    {
        $stateName = $this->stateManager->getAttribute($this->stateManager::stateColumnName());
        assert(is_string($stateName));

        $possibleStateClasses = $this->stateClassConfig()->possibleStates();
        $currentStateClass = $possibleStateClasses[$stateName] ?? null;
        if ($currentStateClass === null) {
            throw new UnknownStateException("The state {$stateName} of {$this->table} with id {$this->id} is not part of {$this->stateClass()}.");
        }

        return new $currentStateClass();
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

    /** @return MorphOne<Model, $this> */
    public function stateManager(): MorphOne
    {
        $stateClass = $this->stateClass();

        return $this->morphOne($stateClass::stateManagerClass(), 'stateable');
    }

    public function stateClassConfig(): StateConfig
    {
        $stateClass = $this->stateClass();

        return $stateClass::config();
    }

    public function stateMachine(): StateMachine
    {
        return new StateMachine($this);
    }
}
