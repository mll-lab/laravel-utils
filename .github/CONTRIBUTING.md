# CONTRIBUTING

## Setup

This project assumes to run in a Linux environment and requires
- [GNU Make](https://www.gnu.org/software/make)
- [Docker](https://www.docker.com)

After cloning the repository, run the following command:

```bash
make setup
```

We are using [GitHub Actions](https://github.com/features/actions) as a continuous integration system.

For details, see [`workflows/validate.yml`](workflows/validate.yml).

## Directory structure

Add any source code to publish (classes, config files) in [src](/src).

Add tests to [tests](/tests).

Add files a user of this package would create in their own application in [app](/app).

## Code Style

We are using [`friendsofphp/php-cs-fixer`](https://github.com/friendsofphp/php-cs-fixer) to automatically format the code.

Run

```bash
make fix
```

to automatically format the code.

## Static Code Analysis

We are using [`phpstan/phpstan`](https://github.com/phpstan/phpstan) to statically analyze the code.

Run

```bash
make stan
```

to run a static code analysis.

## Tests

We are using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit) to drive the development.

Run

```bash
make test
```

to run all the tests.

## Extra lazy?

Run

```bash
make
```

to enforce coding standards, perform a static code analysis, and run tests!

:bulb: Run

```bash
make help
```

to display a list of available targets with corresponding descriptions.
