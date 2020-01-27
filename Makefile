.DEFAULT￿_GOAL :￿= help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
.PHONY: help

##
## Clear Cache
##
clear: ## Clear cache
	bin/console cache:clear
.PHONY: clear

##
## PHPStan Scan
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

