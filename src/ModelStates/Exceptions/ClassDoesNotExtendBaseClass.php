<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

final class ClassDoesNotExtendBaseClass extends InvalidConfig
{
    public static function make(string $class, string $baseClass): self
    {
        return new self("Class {$class} does not extend the `{$baseClass}` base class.");
    }
}
