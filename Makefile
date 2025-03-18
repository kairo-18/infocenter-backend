setup:
	@$(MAKE) build
	@$(MAKE) up
	@$(MAKE) composer-update

build:
	docker-compose build --no-cache --force-rm

stop:
	docker-compose stop

up:
	docker-compose up -d

composer-install:
	docker-compose exec infocenter-backend composer install

composer-update:
	docker-compose exec infocenter-backend composer update

migrate:
	docker-compose exec infocenter-backend php artisan migrate --force

seed:
	docker-compose exec infocenter-backend php artisan db:seed --force

shell:
	docker-compose exec -u 1000:1000 infocenter-backend bash

serve:
	docker-compose exec infocenter-backend php artisan serve --host=0.0.0.0 --port=8000

fix-permissions:
	docker-compose exec -u 1000:1000 infocenter-backend find /var/www -type f ! -path "/var/www/.git/*" -exec chmod 664 {} +
	docker-compose exec -u 1000:1000 infocenter-backend find /var/www -type d ! -path "/var/www/.git*" -exec chmod 775 {} +
