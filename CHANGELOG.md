# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

See [GitHub releases](https://github.com/mll-lab/laravel-utils/releases).

## Unreleased

## v10.4.1

### Changed

- Improve `TransitionNotFound` exception message

## v10.4.0

### Added

- Disallow `getenv()` via PHPStan rule

## v10.3.0

### Added

- Disallow `Illuminate\Database\Eloquent\Model::update()` via PHPStan rule

## v10.2.1

### Fixed

- Allow `Illuminate\Database\Eloquent\Builder::create()` without attributes in PHPStan rule

## v10.2.0

### Added

- Disallow `Illuminate\Support\Collection::flatten()` via PHPStan rule

## v10.1.0

### Added

- Register rules as a PHPStan extension
- Support Laravel 12

## v10.0.0

### Removed

- Drop support for Laravel 9 and 10

### Added

- Add Collection macro `interpose` and `CollectionUtils::flattenOnce`

### Fixed

- Handle empty coordinates in `CoordinatesCast`
- Open class `StateMachine` for inheritance

## v9.0.0

### Changed

- Support multiple `StateManager` classes by configuring the `StateManager`-Model in `StateConfig` and the column name in `StateManager`-Model

### Removed

- Drop support for PHP 8.1

## v8.0.1

### Fixed

- Omit tests from composer package

## v8.0.0

### Changed

- Throw `DuplicateTransitionException` on duplicate state transitions in `StateConfig`

## v7.0.0

### Added

- Add `MLL\LaravelUtils\ModelStates\MermaidStateConfigValidator`

### Removed

- Remove `MLL\LaravelUtils\ModelStates\HasStateManager::generateMermaidGraphAsString`

## v6.0.0

### Changed

- BREAKING CHANGE: `MLL\LaravelUtils\Casts\CoordinatesCast` requires class-string of `MLL\Utils\Microplate\CoordinateSystem` as a parameter

### Removed

- Remove `MLL\LaravelUtils\Casts\Coordinates96Well`

## v5.8.0

### Added

- Add `MLL\LaravelUtils\Casts\CoordinatesCast`

## v5.7.0

### Added

- Support `mll-lab/php-utils` 5

## v5.6.0

### Added

- Support `mll-lab/php-utils` 4

## v5.5.0

### Added

- Support `mll-lab/php-utils` 3

## v5.4.0

### Added

- Add artisan command `laravel-utils:dispatch-job`

## v5.3.0

### Added

- Support `mll-lab/php-utils` 2

## v5.2.1

### Fixed

- Handle `null` in `UnsignedInt` cast

## v5.2.0

### Added

- Round floats in `UnsignedInt` cast

## v5.1.0

### Added

- Add `UnsignedInt` cast

## v5.0.0

### Added

- Add `MLL\LaravelUtils\Casts\Coordinates96Well` from `mll-lab/microplate`

### Changed

- Require `mll-lab/php-utils`

## v4.12.1

### Changed

- Remove unused dependency `nesbot/carbon`

## v4.12.0

### Changed

- Make `php artisan send-test-mail` option `--from` optional

## v4.11.0

### Added

- Support Laravel 11

## v4.10.0

### Added

- Add option `--reply-to` to `php artisan send-test-mail`

## v4.9.0

### Changed

- Limit `ExponentialBackoff` to specifying just `$backoff`

## v4.8.0

### Added

- Allow specifying mailer in `php artisan send-test-mail`

## v4.7.0

### Added

- Add command `send-test-mail`

## v4.6.0

### Added

- Allow calling `->change()` on an enum column definition when using migrations

## v4.5.0

### Changed

- Use single line PHPDoc in `factory.stub`

## v4.4.0

### Added

- Support Laravel 10
- Integrate `mll-lab/laravel-strict-stubs`

## v4.3.0

### Added

- Support `thecodingmachine/safe:^2`

## v4.2.0

### Added

- Add trait `ExponentialBackoff` for queue jobs

## v4.1.0

### Changed

- Add `TransitionDirection` and visibility method for `Transition`-class

## v4.0.0

### Added

- Add conditional migrations

### Removed

- Drop support for Laravel 8

### Changed

- Use union and intersection types in `ModelStates`

## v3.0.0

### Changed

- Define `Transition::$model` as union type `HasStateManagerInterface&Model`

## v2.0.0

### Removed

- Drop PHP 7.4 and PHP 8.0 support

### Changed

- Bump minimum requirements to ensure PHP 8.2 compatibility

### Added

- Add state support for models in a morphed table

## v1.0.0

### Added

- Add `Autoincrement`
