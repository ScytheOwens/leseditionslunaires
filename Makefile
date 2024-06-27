stack_name = les_editions_lunaires
source_tag = dev
php_container_id = $(shell docker ps --filter name="$(stack_name)_php" -q)
user = www-data

# SHELL
.PHONY: shell
shell:
	docker exec -it $(php_container_id) /bin/sh

.PHONY: bash
bash:
	docker exec -it $(php_container_id) /bin/bash

.PHONY: command
command:
	docker exec -it $(php_container_id) $(cmd)

# SYMFONY
.PHONY: console
console:
	docker exec -it "$(php_container_id)" php bin/console $(cmd)

.PHONY: composer
composer:
	docker exec -u www-data -it "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer $(cmd)

# IMAGES
.PHONY: build-image
build-image:
	docker build --build-arg source_tag=$(source_tag) --no-cache --network=host -t leseditionslunaires.fr/website/php-fpm:$(source_tag) -f .docker/Dockerfile .

# STACKS
.PHONY: stack-deploy
stack-deploy:
	docker stack deploy -c .docker/docker-compose.yml ${stack_name}

.PHONY: stack-undeploy
stack-undeploy:
	docker stack rm ${stack_name}
