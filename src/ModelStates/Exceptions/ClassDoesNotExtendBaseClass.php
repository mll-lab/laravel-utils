<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

final class ClassDoesNotExtendBaseClass extends InvalidConfig
{
    public function __construct(string $class, string $baseClass)
    {
        parent::__construct("Class {$class} does not extend the base class {$baseClass}.");
    }
}
