<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

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
            if (property_exists($self, 'stateManager') && null !== $self->stateManager) {
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
        if (null === $stateClass) {
            throw new UnknownStateException("The state {$stateName} of {$this->table} with id {$this->id} is not part of {$this->stateClass()}.");
        }

        return new $stateClass();
    }

    /**
     * @param State|class-string<State> $newState
     */
    public function setStateAttribute($newState): void
    {
        // if the old and new state are the same, do not perform a "transition"
        if ($this->state::name() === $newState::name()) {
            return;
        }

        $stateMachine = new StateMachine($this);
        $stateMachine->transitionTo($newState);
    }

    public function stateManager(): MorphOne
    {
        return $this->morphOne(
            ModelStatesServiceProvider::stateManagerClass(),
            'stateable',
        );
    }

    public function generateMermaidGraphAsString(): string
    {
        $config = $this->stateClassConfig();

        $graph = (new Graph(['abc_order' => true]))
            ->addStyle('linkStyle default interpolate basis');

        $possibleStateInstances = array_map(
            static fn (string $stateClass): State => new $stateClass(),
            $config->possibleStates(),
        );

        foreach ($possibleStateInstances as $state) {
            $graph->addNode(new Node($state::name()));
        }

        foreach ($possibleStateInstances as $state) {
            foreach ($config->possibleNextStates($state) as $possibleNextState) {
                $sourceNode = $graph->getNode($state::name());
                $targetNode = $graph->getNode($possibleNextState::name());

                if (null !== $sourceNode && null !== $targetNode) {
                    $transition = $config->transition(get_class($state), get_class($possibleNextState));
                    assert(null !== $transition);

                    $reflectionClass = new \ReflectionClass($transition);
                    $transitionName = DefaultTransition::class === $reflectionClass->getName()
                        ? ''
                        : $reflectionClass->getShortName();

                    $graph->addLink(new Link($sourceNode, $targetNode, $transitionName));
                }
            }
        }

        return $graph->__toString();
    }

    public function stateClassConfig(): StateConfig
    {
        return $this->stateClass()::config();
    }
}
