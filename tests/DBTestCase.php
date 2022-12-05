<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\DB;

abstract class DBTestCase extends TestCase
{
    public const DEFAULT_CONNECTION = 'mysql';

    /**
     * Indicates if migrations ran.
     */
    protected static bool $migrated = false;

    public function setUp(): void
    {
        parent::setUp();

        if (! static::$migrated) {
            $this->artisan('migrate:fresh', [
                '--path' => __DIR__ . '/Database/migrations',
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
        assert($config instanceof ConfigRepository);

        $config->set('database.default', self::DEFAULT_CONNECTION);

        $mysqlOptions = $this->mysqlOptions();
        $config->set('database.connections.' . self::DEFAULT_CONNECTION, $mysqlOptions);
    }

    /**
     * @return array<string, mixed>
     */
    protected function mysqlOptions(): array
    {
        return [
            'driver' => 'mysql',
            'database' => env('MLL_TEST_DB_DATABASE', 'test'),
            'username' => env('MLL_TEST_DB_USERNAME', 'root'),
            'password' => env('MLL_TEST_DB_PASSWORD', ''),
            'host' => env('MLL_TEST_DB_HOST', 'mysql'),
            'port' => env('MLL_TEST_DB_PORT', '3306'),
            'unix_socket' => env('MLL_TEST_DB_UNIX_SOCKET', null),
        ];
    }
}
