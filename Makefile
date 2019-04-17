#!make
# Makefile for Docker
DIR = docker_varzea
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

logs:
	@cd $(DIR) && docker-compose logs -f

php:
	@cd $(DIR) && docker exec -it $(shell cd $(DIR) && docker-compose ps -q php) bash

php-artisan:
	@cd $(DIR) && docker exec -u $(shell id -u):$(shell id -g) -it $(shell cd $(DIR) && docker-compose ps -q php) bash

certbot:
	@cd $(DIR) && docker-compose -f docker-compose.yml -f docker-compose.certbot.yml up -d
	@docker run -it --rm -v $(DIR)_certs:/etc/letsencrypt -v $(DIR)_certs-data:/data/letsencrypt certbot/certbot certonly --webroot --email admin@asfl.com.br --agree-tos --no-eff-email --webroot-path=/data/letsencrypt -d $(NGINX_HOST) 

certbot-renew:
	@docker run -it --rm -v $(DIR)_certs:/etc/letsencrypt -v $(DIR)_certs-data:/data/letsencrypt certbot/certbot certonly --webroot --email admin@asfl.com.br --agree-tos --no-eff-email --webroot-path=/data/letsencrypt -d $(NGINX_HOST) 

production:
	@cd $(DIR) && docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d