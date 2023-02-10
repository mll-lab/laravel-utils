# laravel-utils

[![Continuous Integration](https://github.com/mll-lab/laravel-utils/workflows/Continuous%20Integration/badge.svg)](https://github.com/mll-lab/laravel-utils/actions)
[![Code Coverage](https://codecov.io/gh/mll-lab/laravel-utils/branch/master/graph/badge.svg)](https://codecov.io/gh/mll-lab/laravel-utils)

[![Latest Stable Version](https://poser.pugx.org/mll-lab/laravel-utils/v/stable)](https://packagist.org/packages/mll-lab/laravel-utils)
[![Total Downloads](https://poser.pugx.org/mll-lab/laravel-utils/downloads)](https://packagist.org/packages/mll-lab/laravel-utils)

Shared Laravel utilities of MLL

## Installation

Install through composer

```sh
composer require mll-lab/laravel-utils
```

## Usage

See [tests](tests).

### Autoincrement

Allows the creation of incrementing IDs without actually using autoincrement.

Extend the class [`Autoincrement`](src/Database/Autoincrement.php) with a descriptive name for your ID.

```php
use MLL\LaravelUtils\Database\Autoincrement;

final class MaxFooId extends Autoincrement
{
    public static function name(): string
    {
        return 'max_foo_id';
    }
}
```

Generate a migration and call the `createTable()` method in it:

```php
public function up() {
    MaxFooId::createTable();
}
```

To use this ID in your model, set `$incrementing` to false and assign the ID to your model in the `booted()` method:

```php
public $incrementing = false;

protected static function booted(): void
{
    self::creating(function (self $instance): void {
        $instance->id ??= MaxFooId::next();
    });
```

### Conditional Migrations

To run a migration conditionally, implement the `MLL\LaravelUtils\Database\ConditionalMigration`
interface and its `->shouldRun()` method in your migration:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use MLL\LaravelUtils\Database\ConditionalMigration

return new class extends Migration implements ConditionalMigration {
    public function up(): void
    {
        // Something that would put intense strain on the database
    }

    public function shouldRun(): bool
    {
        $currentHour = Carbon::now()->hour;

        // Only run between 01:00 and 03:00
        return $currentHour > 1 && $currentHour < 3;
    }
};
```

## Changelog

See [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

See [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## License

This package is licensed using the MIT License.
