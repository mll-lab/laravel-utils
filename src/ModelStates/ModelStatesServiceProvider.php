<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Support\ServiceProvider;

class ModelStatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $configFile = __DIR__ . '/model-state.php';

        $this->publishes([
            $configFile => config_path('model-state.php'),
        ], 'config');
        $this->mergeConfigFrom($configFile, 'model-state');
    }
}
