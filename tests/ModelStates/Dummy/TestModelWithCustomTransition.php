<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy;

use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\ModelStates\HasStateManager;
use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\OtherModelStates\OtherModelState;

/**
 * @property string $id
 * @property string $message
 * @property-read StateManager $stateManager
 */
final class TestModelWithCustomTransition extends Model implements HasStateManagerInterface
{
    use HasStateManager;

    public $timestamps = false;

    public function stateClass(): string
    {
        return OtherModelState::class;
    }

    public function getTable(): string
    {
        return 'test_models';
    }
}
