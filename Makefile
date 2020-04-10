up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down trade-clear docker-pull docker-duild docker-up trade-init
deploy: build-production push-production deploy-production

my:
	sudo chown -R roman:roman trade

my2:
	sudo chmod 777 trade

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-duild:
	docker-compose build

trade-init: trade-composer-install trade-assets-install trade-wait-db trade-migrations trade-fixtures trade-ready

trade-clear:
	docker run --rm -v ${PWD}/trade:/app --workdir=/app alpine rm -f .ready

trade-composer-install:
	docker-compose run --rm trade-php-cli composer install
	docker-compose run --rm trade-node npm rebuild node-sass

trade-assets-install:
	docker-compose run --rm trade-node yarn install

trade-wait-db:
	until docker-compose exec -T trade-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

trade-migrations:
	docker-compose run --rm trade-php-cli php bin/console doctrine:migrations:migrate --no-interaction

trade-fixtures:
	docker-compose run --rm trade-php-cli php bin/console doctrine:fixtures:load --no-interaction

trade-ready:
	docker run --rm -v ${PWD}/trade:/app --workdir=/app alpine touch .ready

trade-test:
	docker-compose run --rm trade-php-cli php bin/phpunit

cli:
	docker-compose run --rm trade-php-cli php bin/app.php

build-production:
	docker build --pull --file=trade/docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/trade-nginx:${IMAGE_TAG} trade
	docker build --pull --file=trade/docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/trade-php-fpm:${IMAGE_TAG} trade
	docker build --pull --file=trade/docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/trade-php-cli:${IMAGE_TAG} trade
	docker build --pull --file=trade/docker/production/postgres.docker --tag ${REGISTRY_ADDRESS}/trade-postgres:${IMAGE_TAG} trade

push-production:
	docker push ${REGISTRY_ADDRESS}/trade-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/trade-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/trade-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/trade-postgres:${IMAGE_TAG}

deploy-production:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "TRADE_APP_SECRET=${TRADE_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "TRADE_DB_PASSWORD=${TRADE_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "TRADE_REDIS_PASSWORD=${TRADE_REDIS_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose up --build -d'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'until docker-compose exec -T trade-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose run --rm trade-php-cli php bin/console doctrine:migrations:migrate --no-interaction'