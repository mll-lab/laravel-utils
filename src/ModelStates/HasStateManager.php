<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;
use MLL\LaravelUtils\ModelStates\Exceptions\UnknownStateException;

trait HasStateManager
{
    /**
     * @return class-string<State>
     */
    abstract public function stateClass(): string;

    public static function bootHasStateManager(): void
    {
        self::created(function (self $self): void {
            if (null !== $self->stateManager) {
                return;
            }
            /** @var class-string $modelClass */
            $modelClass = config('model-state.model');

            $stateManager = new $modelClass();
            $stateManager->{config('model-state.column_name')} = $self->stateClass()::defaultState()::name();
            assert($stateManager instanceof Model);
            $self->stateManager()->save($stateManager);

            $self->setRelation('stateManager', $stateManager);
        });
    }

    public function getStateAttribute(): State
    {
        $stateName = $this->stateManager->{config('model-state.column_name')};

        $stateClasses = $this->getStateClassConfig()->possibleStates();

        $stateClass = $stateClasses[$stateName] ?? null;
        if (null === $stateClass) {
            throw new UnknownStateException("The state {$stateName} of {$this->table} with id {$this->id} is not part of {$this->stateClass()}");
        }

        return new $stateClass();
    }

    /**
     * @param State|class-string<State> $newState
     */
    public function setStateAttribute($newState): void
    {
        // if the old and new state are the same do not perform a "transition"
        if ($this->state == $newState) {
            return;
        }
        $stateMachine = new StateMachine($this);
        $stateMachine->transitionTo($newState);
    }

    public function stateManager(): MorphOne
    {
        /** @var class-string $modelClass */
        $modelClass = config('model-state.model');
        $relationName = config('model-state.relation_name');
        assert(is_string($relationName));

        return $this->morphOne($modelClass, $relationName);
    }

    public function generateMermaidGraphAsString(): string
    {
        $config = $this->getStateClassConfig();

        $graph = (new Graph(['abc_order' => true]))
            ->addStyle('linkStyle default interpolate basis');
        /** @var class-string<State> $stateString */
        foreach ($config->possibleStates() as $stateString) {
            $stateInstance = new $stateString();
            $graph->addNode(new Node($stateInstance::name()));
        }
        foreach ($config->possibleStates() as $stateString) {
            $stateInstance = new $stateString();
            $possibleNextStates = $config->possibleNextStates($stateInstance);
            foreach ($possibleNextStates as $possibleNextState) {
                $sourceNode = $graph->getNode($stateInstance::name());
                $targetNode = $graph->getNode($possibleNextState::name());
                $transition = $config->transition(get_class($stateInstance), get_class($possibleNextState));
                assert(null !== $transition);
                $reflectionClass = new \ReflectionClass($transition);
                $transitionName = DefaultTransition::class === $reflectionClass->getName() ? '' : $reflectionClass->getShortName();

                if (null !== $sourceNode && null !== $targetNode) {
                    $graph->addLink(new Link($sourceNode, $targetNode, $transitionName));
                }
            }
        }

        return $graph->__toString();
    }

    public function getStateClassConfig(): StateConfig
    {
        /** @var StateConfig $config */
        $config = $this->stateClass()::config();

        return $config;
    }
}
