<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Exceptions;

final class DuplicateTransitionException extends \Exception
{
    public function __construct(string $from, string $to)
    {
        parent::__construct("There is already a transition from {$from} to {$to}.");
    }
}
