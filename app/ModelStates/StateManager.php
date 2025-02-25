<?php declare(strict_types=1);

namespace App\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\ModelStates\IsStateManager;

/**
 * @property string $state_name
 */
final class StateManager extends Model
{
    use IsStateManager;

    /** @return MorphTo<HasStateManagerInterface&Model, $this> */
    public function stateable(): MorphTo // @phpstan-ignore method.childReturnType
    {
        return $this->morphTo(); // @phpstan-ignore return.type
    }

    public static function stateColumnName(): string
    {
        return 'state_name';
    }
}
