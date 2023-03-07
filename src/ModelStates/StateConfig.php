<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use MLL\LaravelUtils\ModelStates\Exceptions\ClassDoesNotExtendBaseClass;

final class StateConfig
{
    /**
     * @var array<
     *      class-string<State>,
     *      array<class-string<State>, class-string<Transition>|null>
     * >
     */
    public array $allowedTransitions = [];

    /**
     * @param class-string<State>|array<class-string<State>> $fromOrFroms
     * @param class-string<State> $to
     * @param class-string<Transition> $transition
     */
    public function allowTransition(string|array $fromOrFroms, string $to, string $transition = DefaultTransition::class): StateConfig
    {
        if (is_array($fromOrFroms)) {
            foreach ($fromOrFroms as $from) {
                $this->allowTransition($from, $to, $transition);
            }

            return $this;
        }

        // @phpstan-ignore-next-line php-stan is not right here
        if (! is_subclass_of($fromOrFroms, State::class)) {
            throw new ClassDoesNotExtendBaseClass($fromOrFroms, State::class);
        }

        // @phpstan-ignore-next-line php-stan is not right here
        if (! is_subclass_of($to, State::class)) {
            throw new ClassDoesNotExtendBaseClass($to, State::class);
        }

        // There might already be transitions registered for this state
        $fromTransition = $this->allowedTransitions[$fromOrFroms] ?? [];

        // We just overwrite the transition with what was defined.
        // We might consider throwing an exception here to catch double configuration.
        $fromTransition[$to] = $transition;

        $this->allowedTransitions[$fromOrFroms] = $fromTransition;

        return $this;
    }

    /**
     * @return class-string<Transition>
     */
    public function transition(string $fromMorphClass, string $toMorphClass): ?string
    {
        $fromTransition = $this->allowedTransitions[$fromMorphClass] ?? null;
        if (null === $fromTransition) {
            return null;
        }

        return $fromTransition[$toMorphClass] ?? null;
    }

    /**
     * Returns a map from state names to state classes.
     *
     * @return array<string, class-string<State>>
     */
    public function possibleStates(): array
    {
        $states = [];

        foreach ($this->allowedTransitions as $fromState => $to) {
            $states[$fromState::name()] = $fromState;

            foreach (array_keys($to) as $toState) {
                $states[$toState::name()] = $toState;
            }
        }

        return $states;
    }

    /**
     * Returns a possibly empty list of the next possible state that can be transitioned to from the given state.
     *
     * @return SupportCollection<class-string<State>, State>
     */
    public function possibleNextStates(State $from): SupportCollection
    {
        $fromClass = $from::class;

        if (! isset($this->allowedTransitions[$fromClass])) {
            // @phpstan-ignore-next-line empty colletion is part of SupportCollection<class-string<State>, State>
            return new SupportCollection();
        }

        $next = [];
        foreach (array_keys($this->allowedTransitions[$fromClass]) as $stateClass) {
            $next[$stateClass] = new $stateClass();
        }

        // @phpstan-ignore-next-line php-stan is not right here
        return new SupportCollection($next);
    }

    /**
     * Returns a possibly empty list of the next possible transitions for a given model with StateManager.
     *
     * @return SupportCollection<array-key, Transition>
     */
    public function possibleNextTransitions(Model&HasStateManagerInterface $stateable): SupportCollection
    {
        $stateable->refresh();
        $from = $stateable->state;
        $possibleNextStates = $this->possibleNextStates($from);

        $stateMachine = $stateable->stateMachine();

        return $possibleNextStates
            ->map(fn (State $nextState): Transition => $stateMachine->instantiateTransitionClass($from::class, $nextState::class))->values();
    }
}
