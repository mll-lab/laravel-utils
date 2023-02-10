<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\DB;
use MLL\LaravelUtils\Database\DatabaseServiceProvider;
use MLL\LaravelUtils\ModelStates\ModelStatesServiceProvider;

abstract class DBTestCase extends TestCase
{
    public const DEFAULT_CONNECTION = 'mariadb';

    /**
     * Indicates if migrations ran.
     */
    protected static bool $migrated = false;

    public function setUp(): void
    {
        parent::setUp();

        if (! static::$migrated) {
            $this->artisan('migrate:fresh', [
                '--path' => __DIR__ . '/../app/migrations',
                '--realpath' => true,
            ]);

            static::$migrated = true;
        }

        // Ensure we start from a clean slate each time
        // We cannot use transactions, as they do not reset autoincrement
        $databaseName = env('MLL_TEST_DB_DATABASE') ?? 'mll';
        assert(is_string($databaseName));

        $columnName = "Tables_in_{$databaseName}";
        foreach (DB::select('SHOW TABLES') as $table) {
            assert($table instanceof \stdClass);
            DB::table($table->{$columnName})->truncate();
        }
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $config = $app->make(ConfigRepository::class);
        // @phpstan-ignore-next-line unknown with Laravel 8, known with Laravel 9
        assert($config instanceof ConfigRepository);

        $config->set('database.default', self::DEFAULT_CONNECTION);

        $mariadbOptions = $this->mariadbOptions();
        $config->set('database.connections.' . self::DEFAULT_CONNECTION, $mariadbOptions);
    }

    protected function getPackageProviders($app): array
    {
        return [
            DatabaseServiceProvider::class,
            ModelStatesServiceProvider::class,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mariadbOptions(): array
    {
        return [
            'driver' => 'mysql',
            'database' => env('MLL_TEST_DB_DATABASE', 'test'),
            'username' => env('MLL_TEST_DB_USERNAME', 'root'),
            'password' => env('MLL_TEST_DB_PASSWORD', ''),
            'host' => env('MLL_TEST_DB_HOST', 'mariadb'),
            'port' => env('MLL_TEST_DB_PORT', '3306'),
            'unix_socket' => env('MLL_TEST_DB_UNIX_SOCKET', null),
        ];
    }
}
