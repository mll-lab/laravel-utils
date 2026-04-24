# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
make setup # Always run first, updates deps and starts containers
make it    # Whole project validation
```

See `Makefile` for more targeted commands.

## Key Constraints

- This is a **library** — classes must NOT be `final`
- `rules.neon` ships PHPStan disallowed-calls rules to consumers — changes affect downstream projects
- `app/` contains test fixtures (models, migrations, state machines), not application code
- Tests requiring MariaDB extend `DBTestCase`, others extend `TestCase` (both from this repo's `tests/`)
