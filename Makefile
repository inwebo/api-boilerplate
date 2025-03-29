# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
PHP-CS-FIXER = $(PHP) vendor/bin/php-cs-fixer
PHPSTAN = $(PHP) vendor/bin/phpstan
PHPUNIT  = $(PHP) vendor/bin/phpunit
PHPMD  = $(PHP) vendor/bin/phpmd
NPM = npm

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test build-from-cache

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

build-from-cache: ## Builds the Docker images from cache
	@$(DOCKER_COMP) build --pull

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)


## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

## —— App 🧰 ———————————————————————————————————————————————————————————————
.PHONY        : init-db tests php-cs-fixer php-cs-lint phpstan tests phpmd
php-cs-fixer:
	@$(PHP-CS-FIXER) fix -vvv

php-cs-lint:
	@$(PHP-CS-FIXER) fix --dry-run -vvv

init-db:
	@$(SYMFONY) doctrine:schema:drop --force
	@$(SYMFONY) doctrine:schema:create
	@$(SYMFONY) doctrine:schema:update
	@$(SYMFONY) doctrine:fixtures:load --append

phpstan:
	@$(PHPSTAN)

phpmd:
	@$(PHPMD) ./src html ./phpmd.rulesets.xml --reportfile var/phpmd-report.html

tests:
	@$(SYMFONY) doctrine:schema:drop --force --full-database --env=test || true
	@$(SYMFONY) doctrine:schema:create --env=test
	@$(SYMFONY) doctrine:schema:update --complete --force --env=test
	@$(SYMFONY) doctrine:fixtures:load --append --env=test
	@$(PHPUNIT)
