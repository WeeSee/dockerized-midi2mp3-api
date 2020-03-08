#
# build-tool
# (C) weesee@web.de, 2020
#

.PHONY: help build

help:
	@echo "# Build-Tool"
	@echo "# (C) weesee@web.de, 2020"
	@echo Invocation: make [command]
	@echo Commands:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# hier die befehle
build: ## alle Container bauen
	docker-compose build
	
up:	## start containers
	docker-compose up -d --remove-orphans

down: ## stop containers
	docker-compose down

bash: ## open bash in app container
	docker-compose exec midi2mp3-api bash
	
logs: ## show container logs and follow untail ^c
	docker-compose logs -f -t
    
ps: ## see what's running
	docker-compose ps

push: ## push repo do Dockerhub
	docker login \
	&& docker-compose push midi2mp3-api \
	&& docker logout
