.DEFAULT_GOAL := help
help: ## list of available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
.PHONY: help

##
## Project
## ----
##
## Installation
## ----
##
install: db ## Install and start the project
.PHONY: install

composer.lock: composer.json
	composer update

vendor: composer.lock
	composer install

.env:
	@if [ -f .env ]; \
	then\
		echo ".env.dist file changed. Check your .env file";\
		touch .env;\
		exit 1;\
	else\
		echo cp .env.dist .env;\
	  	cp .env.dist .env;\
	fi

db: .env vendor ## Reset the database
	-bin/console doctrine:database:create --if-not-exists
	bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
.PHONY: db

##
## Clear Cache
## ----
##
clear: ## Clear cache
	bin/console cache:clear
.PHONY: clear

##
## PHPStan Scan
## ----
##
scan: ## Scan with phpstan
	vendor/bin/phpstan analyse
.PHONY: scan

##
## Tests
## ----
##

test: unit functional ## Run unit and functional tests

unit: ## Run unit tests
	bin/phpunit tests --exclude-group functional

functional: ## Run functional tests
	bin/phpunit tests --group functional

.PHONY: test unit functional

##
## (After downloading dart-sass to the root directory)
## Sass compile
## ----
##

watch: ## Watch stylesheetps and recompile when change
	./dart-sass/sass public/sass/style.scss public/css/style.css --watch
.PHONY: watch

compile: ## Compile Sass to CSS
	./dart-sass/sass public/sass/style.scss public/css/style.css
.PHONY: compile
