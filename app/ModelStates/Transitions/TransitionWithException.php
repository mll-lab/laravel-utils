<?php declare(strict_types=1);

namespace App\ModelStates\Transitions;

use App\ModelStates\TestModelWithCustomTransition;
use Illuminate\Database\Eloquent\Model;
use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\ModelStates\Transition;

final class TransitionWithException extends Transition
{
    /**
     * @var HasStateManagerInterface&TestModelWithCustomTransition
     */
    protected HasStateManagerInterface&Model $model; // @phpstan-ignore-line

    public function handle(): TestModelWithCustomTransition
    {
        $this->manage();

        throw new \Exception('This is a exception thrown by TransitionWithException.');
    }
}
