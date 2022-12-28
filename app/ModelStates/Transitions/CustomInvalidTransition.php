<?php declare(strict_types=1);

namespace App\ModelStates\Transitions;

use MLL\LaravelUtils\ModelStates\Transition;

final class CustomInvalidTransition extends Transition
{
    public function canTransition(): bool
    {
        return false;
    }
}
