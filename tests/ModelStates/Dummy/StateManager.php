<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use MLL\LaravelUtils\ModelStates\IsStateManager;

final class StateManager extends Model
{
    use IsStateManager;

    public function stateable(): MorphTo
    {
        $relationName = config('model-state.relation_name');

        return $this->morphTo(__FUNCTION__, $relationName . '_type', $relationName . '_id');
    }
}
