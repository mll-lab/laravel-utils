<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates\Dummy\Transitions;

use MLL\LaravelUtils\ModelStates\HasStateManagerInterface;
use MLL\LaravelUtils\ModelStates\Transition;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\TestModelWithCustomTransition;

final class CustomTransition extends Transition
{
    /**
     * @var HasStateManagerInterface&TestModelWithCustomTransition
     */
    protected HasStateManagerInterface $model; // @phpstan-ignore-line

    public function handle(): TestModelWithCustomTransition
    {
        $this->manage();

        $this->model->message = 'my custom action';
        $this->model->save();

        return $this->model;
    }
}
