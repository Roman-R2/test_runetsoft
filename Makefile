up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down tires-clear docker-pull docker-duild docker-up tires-init

my:
	sudo chown -R roman:roman tires

my2:
	sudo chmod 777 tires

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

tires-init: tires-wait-db tires-ready

tires-clear:
	docker run --rm -v ${PWD}/tires:/app --workdir=/app alpine rm -f .ready

tires-wait-db:
	until docker-compose exec -T tires-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

tires-ready:
	docker run --rm -v ${PWD}/tires:/app --workdir=/app alpine touch .ready
