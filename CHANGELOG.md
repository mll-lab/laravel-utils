# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

See [GitHub releases](https://github.com/mll-lab/laravel-utils/releases).

## Unreleased

### Changed

- Remove unused dependency nesbot/carbon
- Support docker compose plugin in addition to docker-compose

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
