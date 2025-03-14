<?php declare(strict_types=1);

namespace MLL\LaravelUtils;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use MLL\LaravelUtils\Collection\CollectionMixin;
use MLL\LaravelUtils\Queue\DispatchJob;

class LaravelUtilsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/stubs' => $this->app->basePath('stubs'),
        ], ['strict-stubs']);

        $this->commands([
            DispatchJob::class,
        ]);

        Collection::mixin(new CollectionMixin());
    }
}
