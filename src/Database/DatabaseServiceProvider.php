<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Doctrine\DBAL\Types\Type;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend('migrator', static fn ($_, Container $app): ConditionalMigrator => new ConditionalMigrator(
            $app->make('migration.repository'),
            $app->make('db'),
            $app->make('files'),
            $app->make('events'),
        ));
    }

    public function boot(): void
    {
        // Not included by default in Laravel
        if (class_exists(Type::class) && ! Type::hasType(DoctrineDBALEnumType::NAME)) {
            Type::addType(DoctrineDBALEnumType::NAME, DoctrineDBALEnumType::class);
        }
    }
}
