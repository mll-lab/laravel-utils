<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy;

use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\ModelStates\HasStateManager;
use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates\ModelState;

/**
 * @property string $id
 * @property-read StateManager $stateManager
 */
final class TestModel extends Model implements HasStateManagerInterface
{
    use HasStateManager;

    public function getTable(): string
    {
        return 'test_models';
    }

    public function stateClass(): string
    {
        return ModelState::class;
    }
}
