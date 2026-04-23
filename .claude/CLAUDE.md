# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
make setup        # Always run first, updates deps and starts containers
make it           # Whole project validation
make test         # Run tests with PHPUnit
make stan         # Run static analysis with PHPStan
make fix          # Fix code style (rector + php-cs-fixer)
make rector       # Run rector only
make php-cs-fixer # Run php-cs-fixer only
make build        # Build the local Docker containers
make up           # Bring up the docker compose stack
```

## Key Constraints

- This is a **library** — classes must NOT be `final`
- `rules.neon` ships PHPStan disallowed-calls rules to consumers — changes affect downstream projects
- `app/` contains test fixtures (models, migrations, state machines), not application code
- Tests requiring MariaDB extend `DBTestCase`, others extend `TestCase` (both from this repo's `tests/`)
