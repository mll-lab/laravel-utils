<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use BenSampo\Enum\Enum;

/**
 * @method static static FORWARD()
 * @method static static REVERSE()
 */
class TransitionDirection extends Enum
{
    final public const FORWARD = 'FORWARD';
    final public const REVERSE = 'REVERSE';
}
