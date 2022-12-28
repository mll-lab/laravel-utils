<?php declare(strict_types=1);

namespace App\ModelStates;

use App\ModelStates\OtherModelStates\OtherModelState;
use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\ModelStates\HasStateManager;
use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;

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
