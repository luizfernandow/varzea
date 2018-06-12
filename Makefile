# Makefile for Docker

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

build:
	@cd docker_varzea && docker-compose build

start:
	@cd docker_varzea && docker-compose up -d

stop:
	@cd docker_varzea && docker-compose down -v

logs:
	@cd docker_varzea && docker-compose logs -f

php:
	@cd docker_varzea && docker exec -u $(id -u):$(id -u) -it $(shell cd docker_varzea && docker-compose ps -q php) bash

production:
	@cd docker_varzea && docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d