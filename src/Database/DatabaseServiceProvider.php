<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class DatabaseServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app->extend('migrator', static fn ($_, Container $app): Migrator => new Migrator(
            $app->make('migration.repository'),
            $app->make('db'),
            $app->make('files'),
            $app->make('events'),
        ));
    }
}
