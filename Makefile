dcphp=$$(echo "docker-compose exec php")

.PHONY: it
it: fix stan test ## Run the commonly used targets

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: setup
setup: build vendor ## Setup the local environment

.PHONY: build
build: ## Build the local Docker containers
	docker-compose build --pull --build-arg USER_ID=$(shell id --user) --build-arg GROUP_ID=$(shell id --group)

.PHONY: up
up: ## Bring up the docker-compose stack
	docker-compose up --detach

.PHONY: fix
fix: rector php-cs-fixer

.PHONY: rector
rector: up
	${dcphp} vendor/bin/rector process

.PHONY: php-cs-fixer
php-cs-fixer: up
	${dcphp} vendor/bin/php-cs-fixer fix

.PHONY: stan
stan: up ## Runs a static analysis with phpstan
	${dcphp} vendor/bin/phpstan

.PHONY: test
test: up ## Runs auto-review, unit, and integration tests with phpunit
	${dcphp} vendor/bin/phpunit

vendor: up composer.json
	${dcphp} composer update
	${dcphp} composer validate --strict
	${dcphp} composer normalize

.PHONY: php
php: up ## Open an interactive shell into the PHP container
	${dcphp} bash
