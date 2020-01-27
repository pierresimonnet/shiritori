.DEFAULT￿_GOAL :￿= help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
.PHONY: help

##
## PHPStan
##
scan: ## fait un scan phpstan
	vendor/bin/phpstan analyse
.PHONY: scan

##
## Tests
##
test: ## fait les tests
	bin/phpunit tests
.PHONY: test
