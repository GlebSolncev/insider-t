
help:
	@echo "-- list commands --"
	@echo "cli - open bash to php-fpm container"
	@echo "up - start env working"
	@echo "build	- Build containers"
	@echo "down	- Stop and remove containers"

cli:
	@docker-compose --env-file=.env exec asfapp /bin/bash

build:
	@docker-compose --env-file=.env build --no-cache

up:
	@docker-compose --env-file=.env up -d

down:
	@docker-compose down
