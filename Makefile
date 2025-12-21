.PHONY: help install test coverage analyze format check clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	composer install

test: ## Run tests
	composer test

coverage: ## Generate code coverage report
	composer test:coverage
	@echo "Coverage report generated in coverage/index.html"

analyze: ## Run static analysis
	composer analyze

format: ## Fix code style
	composer format

format-check: ## Check code style without fixing
	composer format:check

check: ## Run all quality checks
	composer check

clean: ## Clean generated files
	rm -rf vendor coverage .phpunit.cache .phpstan.cache

update: ## Update dependencies
	composer update

ci: install check ## Run CI checks locally
	@echo "All CI checks passed!"
