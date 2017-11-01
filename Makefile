.PHONY:test docker-up generate-data php-cs-fix

test:
	docker-compose exec php ./vendor/bin/phpunit

docker-up:
	docker-compose up -d --force-recreate --build

generate-data:
	run-parts ./development

php-cs-fix:
	./vendor/bin/php-cs-fixer fix --allow-risky yes --show-progress run-in
