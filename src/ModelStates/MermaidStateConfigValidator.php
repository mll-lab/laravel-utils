<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;
use PHPUnit\Framework\Assert;

/** Requires the package jbzoo/mermaid-php. */
class MermaidStateConfigValidator
{
    /** Performs a PHPUnit assertion that a generated Mermaid graph equals an expected result. */
    public static function assertGraphEquals(string $expectedGraphString, Graph $actualGraph): void
    {
        Assert::assertSame(
            $expectedGraphString,
            $actualGraph->__toString(),
            "The Mermaid graph did not match. See the actual generated graph at {$actualGraph->getLiveEditorUrl()}."
        );
    }

    public static function assertStateConfigEquals(string $expectedGraphString, StateConfig $actualStateConfig): void
    {
        self::assertGraphEquals(
            expectedGraphString: $expectedGraphString,
            actualGraph: self::graphFromStateConfig($actualStateConfig),
        );
    }

    public static function graphFromStateConfig(StateConfig $stateConfig): Graph
    {
        $graph = (new Graph(['abc_order' => true]))
            ->addStyle('linkStyle default interpolate basis');

        $possibleStateInstances = array_map(
            static fn (string $stateClass): State => new $stateClass(),
            $stateConfig->possibleStates(),
        );

        foreach ($possibleStateInstances as $state) {
            $graph->addNode(new Node($state::name()));
        }

        foreach ($possibleStateInstances as $state) {
            foreach ($stateConfig->possibleNextStates($state) as $possibleNextState) {
                $sourceNode = $graph->getNode($state::name());
                $targetNode = $graph->getNode($possibleNextState::name());

                if ($sourceNode instanceof Node && $targetNode instanceof Node) {
                    $transition = $stateConfig->transition($state::class, $possibleNextState::class);
                    assert($transition !== null);

                    $reflectionClass = new \ReflectionClass($transition);
                    $transitionName = $reflectionClass->getName() === DefaultTransition::class
                        ? ''
                        : $reflectionClass->getShortName();

                    $graph->addLink(new Link($sourceNode, $targetNode, $transitionName));
                }
            }
        }

        return $graph;
    }
}
