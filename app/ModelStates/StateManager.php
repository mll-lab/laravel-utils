<?php declare(strict_types=1);

namespace App\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use MLL\LaravelUtils\ModelStates\IsStateManager;

/**
 * @property string $state_name
 */
final class StateManager extends Model
{
    use IsStateManager;

    public function stateable(): MorphTo
    {
        return $this->morphTo();
    }
}
