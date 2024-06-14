<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Queue;

use Illuminate\Console\Command;

final class DispatchJob extends Command
{
    protected $signature = 'laravel-utils:dispatch-job {class}';

    protected $description = 'Dispatch a job with the given class';

    public function handle(): void
    {
        $class = $this->argument('class');
        if (! is_string($class)) {
            $notString = gettype($class);
            throw new \Exception("Expected argument 'class' to be string, got {$notString}.");
        }
        if (! class_exists($class)) {
            throw new \Exception("Job class {$class} does not exist.");
        }

        if (! method_exists($class, 'dispatch')) {
            throw new \Exception("Job class {$class} does not have a method 'dispatch'.");
        }
        $class::dispatch();
    }
}
