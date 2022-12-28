<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\Transitions;

use MLL\LaravelUtils\ModelStates\Transition;

final class CustomInvalidTransition extends Transition
{
    public function canTransition(): bool
    {
        return false;
    }
}
