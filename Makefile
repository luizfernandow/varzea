#!make
# Makefile for Docker
.PHONY : build rebuild start stop nuxt-start nuxt-stop nuxt-logs nuxt-build logs php php-artisan production certbot certbot-renew

DIR = docker
envfile := $(DIR)/.env 
include $(envfile)
export $(shell sed 's/=.*//' $(envfile))

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  build        Build images"
	@echo "  start        Create and start containers"
	@echo "  stop         Stop all services"
	@echo "  logs         Follow log output"
	@echo "  php          Open php container"
	@echo "  php-artisan  Open php container with artisan"
	@echo "  certbot      Create first certificate"
	@echo "  certbot-renew Renew certificate"
	@echo "  production    Start production envirioment"

build:
	@cd $(DIR) && docker-compose build

rebuild:
	@cd $(DIR) && docker-compose build --no-cache

start:
	@cd $(DIR) && docker-compose up -d

stop:
	@cd $(DIR) && docker-compose down -v

nuxt-start:
	@cd $(DIR) && docker-compose -p nuxt-varzea -f docker-compose.front.dev.yml up -d

nuxt-stop:
	@cd $(DIR) && docker-compose -p nuxt-varzea -f docker-compose.front.dev.yml down -v

nuxt-logs:
	docker logs -f nuxt-varzea

nuxt-build:
	docker run --rm -v $(shell pwd)/application:/app -w="/app" -it node:16 bash -c "npm install && npm run build"

nuxt-generate:
	docker run --rm -v $(shell pwd)/application:/app -w="/app" -it node:16 bash -c "npm run generate"
		
logs:
	@cd $(DIR) && docker-compose logs -f

php:
	@cd $(DIR) && docker exec -it $(shell cd $(DIR) && docker-compose ps -q php) bash

php-artisan:
	@cd $(DIR) && docker exec -u $(shell id -u):$(shell id -g) -it $(shell cd $(DIR) && docker-compose ps -q php) bash

production:
	@cd $(DIR) && docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d

certbot:
	@cd $(DIR) && docker-compose -f docker-compose.yml -f docker-compose.certbot.yml up -d
	@docker run -it --rm -v $(DIR)_certs:/etc/letsencrypt -v $(DIR)_certs-data:/data/letsencrypt certbot/certbot certonly --webroot --email admin@asfl.com.br --agree-tos --no-eff-email --webroot-path=/data/letsencrypt -d $(NGINX_HOST) -d www.$(NGINX_HOST) 

certbot-renew:
	@docker run  --rm -v $(DIR)_certs:/etc/letsencrypt -v $(DIR)_certs-data:/data/letsencrypt certbot/certbot renew -q --webroot --email admin@asfl.com.br --agree-tos --no-eff-email --webroot-path=/data/letsencrypt
	@cd $(DIR) && docker restart $(shell cd $(DIR) && docker-compose ps -q web)

#cat dump.sql | docker exec -i db-varzea psql -h localhost -U varzea_user varzea
