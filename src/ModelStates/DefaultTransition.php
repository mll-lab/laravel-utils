<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

final class DefaultTransition extends Transition
{
    public function handle(): HasStateManagerInterface
    {
        $this->manage();

        return $this->model;
    }
}
