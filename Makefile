up:
	docker compose up -d
down:
	docker compose down
php-bash:
	docker compose exec php-library bash
php-logs:
	docker compose logs php-library
nginx-bash:
	docker compose exec nginx-library bash
nginx-logs:
	docker compose logs nginx-library
vendor:
	docker compose exec php-library bash -c "composer install"
install:
	docker compose exec php-library bash -c "php composer require symfony/runtime"
migrate:
	@docker compose exec php-library bash -c "php bin/console make:migration" && \
	docker compose exec php-library bash -c "php bin/console doctrine:migrations:migrate"
fixture:
	docker compose exec php-library bash -c "php bin/console doctrine:fixtures:load"
delete-authors:
	docker compose exec php-library bash -c "php bin/console RemoveAuthorsWithoutBooks"
