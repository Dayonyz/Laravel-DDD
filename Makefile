include .env
projectPath=$(shell pwd)
serviceList=php nginx postgres redis
laradockFolder=.docker
sshContainer=php
sshContainerName=${APP_NAME}-php-fpm
dbContainer=postgres
dbName=laravel

.DEFAULT_GOAL:=help

re-build: ## Builds docker-compose
	cp .env.example .env && docker-compose build $(serviceList) && \
	cp /app/Domains/Deal/Framework/.env.example /app/Domains/Deal/Framework/.env && \
	cp /app/Domains/IdentityAccess/Framework/.env.example /app/Domains/IdentityAccess/Framework/.env

build: ## Builds docker-compose
	cp .env.example .env && docker-compose build --no-cache $(serviceList) && \
	cp /app/Domains/Deal/Framework/.env.example /app/Domains/Deal/Framework/.env && \
	cp /app/Domains/IdentityAccess/Framework/.env.example /app/Domains/IdentityAccess/Framework/.env

install: ## First installation
	make restart && docker exec $(sshContainerName) bash -c "composer install && composer dump-autoload && \
	php artisan key:generate binder && \
	php artisan storage:link binder && \
	php artisan cache:clear deal && \
	php artisan config:clear deal && \
	php artisan route:clear deal && \
	php artisan key:generate deal && \
	php artisan storage:link deal && \
	php artisan app:db-create deal && \
	php artisan migrate:fresh deal && \
	php artisan cache:clear identity-access && \
	php artisan config:clear identity-access && \
	php artisan route:clear identity-access && \
	php artisan key:generate identity-access && \
    php artisan storage:link identity-access && \
    php artisan app:db-create identity-access && \
	php artisan migrate:fresh identity-access"

kill: ## Stops all docker containers
	docker stop $(shell docker ps -aq)

start: ## Starts docker-compose
	docker-compose up -d $(serviceList)

stop: ## Stops docker-compose
	docker-compose down --volumes

restart: ## Stops docker-compose and starts docker-compose
	make stop && make start

ssh: ## SSH to docker-compose
	docker-compose exec $(sshContainer) bash

prune: ## SSH to docker-compose
	docker system prune -a

ihm: ## Creates models hints
	make laradock-exec CMD="php artisan ide-helper:models $(MODEL) --nowrite"

mfs: ## Migrate fresh + seed
	make laradock-exec CMD="php artisan migrate:fresh --seed"

cache: ## Caches configs and routes
	make laradock-exec CMD="php artisan config:cache && php artisan route:cache"

cacheClear: ## Clears cache of configs and routes
	make laradock-exec CMD="php artisan config:clear && php artisan route:clear"

phpstan-server: ## Static code analyzer for remote
	make laradock-exec CMD="composer phpstan-server"

phpstan: ## Static code analyzer for local
	make laradock-exec CMD="composer phpstan"

cs-server: ## PHPcs server run
	make laradock-exec CMD="composer phpcs-server"

cs: ## PHPcs
	make laradock-exec CMD="composer phpcs"

cs-fixer: ## PHPcs fixer
	make laradock-exec CMD="./vendor/bin/phpcbf --standard=phpcs.xml"

##@ System

chmod: ## Make required chmoding for Linux after creating resources from-inside the container
	chmod 777 -R app/* database/* tests/* storage/* config/* vendor/* .env bootstrap/* resources/*

laradock-exec:
	cd $(laradockFolder) && docker-compose exec $(sshContainer) bash -c "$(CMD)"

conf-setup: ## Conf.d setup
	cd $(dockerFolder) && \
	cp cron/local/* cron/ && \
	cp nginx/local/* nginx && \
	cp nginx/local/.htpasswd nginx && \
	cp -r php/local/* php
