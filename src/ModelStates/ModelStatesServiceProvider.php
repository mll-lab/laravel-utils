<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @return class-string<Model>
     */
    public static function stateManagerClass(): string
    {
        $config = Container::getInstance()->make(ConfigRepository::class);
        // @phpstan-ignore-next-line unknown with Laravel 8, known with Laravel 9
        assert($config instanceof ConfigRepository);

        $modelClass = $config->get('model-state.model');
        assert(is_string($modelClass) && is_subclass_of($modelClass, Model::class));

        return $modelClass;
    }

    public static function stateColumnName(): string
    {
        $config = Container::getInstance()->make(ConfigRepository::class);
        // @phpstan-ignore-next-line unknown with Laravel 8, known with Laravel 9
        assert($config instanceof ConfigRepository);

        $columnName = $config->get('model-state.column_name');
        assert(is_string($columnName));

        return $columnName;
    }
}
