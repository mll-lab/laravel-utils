<?php declare(strict_types=1);

namespace App\ModelStates\Transitions;

use MLL\LaravelUtils\ModelStates\Transition;
use MLL\LaravelUtils\ModelStates\TransitionDirection;

final class CustomInvalidTransition extends Transition
{
    public function canTransition(): bool
    {
        return false;
    }

    public function direction(): TransitionDirection
    {
        return TransitionDirection::REVERSE;
    }

    public function isVisibleFromAdminFrontend(): bool
    {
        return false;
    }
}
