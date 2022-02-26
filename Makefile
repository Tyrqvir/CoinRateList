help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

tests: ## Run all tests
	docker-compose exec app bash -c "./vendor/codeception/codeception/codecept run -f"
.PHONY: tests

clear-cache: ## Run clear cache
	docker-compose exec app bash -c "./bin/console cache:clear"
.PHONY: clear-cache

psalm: ## Run psalm
	docker-compose exec app bash -c "./vendor/bin/psalm"
.PHONY: psalm

recipes:## Show recipes
	docker-compose exec app bash -c "composer recipes"
.PHONY: recipes

composer-init: ## Composer install
	docker-compose exec app bash -c "composer install"
.PHONY: composer-init

exec-app: ## docker exec
	docker-compose exec app bash
.PHONY: exec

db-migrate: ## db migrate
	docker-compose exec app bash -c "./bin/console doctrine:migration:migrate"
.PHONY: build

rate-seed: ## db migrate
	docker-compose exec app bash -c "./bin/console rate:init"
.PHONY: build

build: ## docker-compose build up
	docker-compose up -d --build
.PHONY: build

local-deploy: build composer-init clear-cache db-migrate rate-seed tests ## local deploy
.PHONY: local-deploy

.DEFAULT_GOAL := help
