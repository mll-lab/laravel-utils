<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

enum TransitionDirection
{
    case FORWARD;
    case REVERSE;
}
