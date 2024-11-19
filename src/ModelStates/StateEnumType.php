<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use GraphQL\Type\Definition\EnumType;

/**
 * A convenience wrapper for registering enums programmatically.
 */
final class StateEnumType extends EnumType
{
    /** @var class-string<State> */
    protected string $stateClass;

    /**
     * Create a GraphQL enum from a State class.
     *
     * @param class-string<State> $stateClass
     * @param string|null $name The name the enum will have in the schema, defaults to the basename of the given class
     */
    public function __construct(string $stateClass, ?string $name = null)
    {
        // @phpstan-ignore-next-line PHPStan is not right here
        if (! is_subclass_of($stateClass, State::class)) {
            $abstractState = State::class;
            throw new \InvalidArgumentException("Must pass an instance of {$abstractState}, got {$stateClass}.");
        }
        $this->stateClass = $stateClass;

        $config = $stateClass::config();

        parent::__construct([
            'name' => $name ?? class_basename($stateClass),
            'values' => array_map(
                /**
                 * @return array<string, mixed> Used to construct a \GraphQL\Type\Definition\EnumValueDefinition
                 */
                fn (string $state): array => [
                    'name' => $state::name(),
                    'value' => new $state(),
                ],
                $config->possibleStates()
            ),
        ]);
    }

    /** Overwrite the native EnumType serialization, as this class does not hold plain values. */
    public function serialize($value): string
    {
        if ($value instanceof State) {
            return $value::name();
        }

        if (is_string($value)) {
            // We just assume the name itself was returned
            return $value;
        }

        $notStateOrString = gettype($value);
        throw new \InvalidArgumentException("Expected a value of type State|string, got: {$notStateOrString}.");
    }
}
