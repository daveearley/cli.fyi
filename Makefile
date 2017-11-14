.PHONY:test docker-up generate-data php-cs-fix deploy rollback-deploy monitor-cache flush-cache

phpunit:
	docker-compose exec php ./vendor/bin/phpunit $(ARGS)

docker-up:
	docker-compose up -d --force-recreate --build

docker-logs:
	docker-compose logs -f

generate-data:
	run-parts ./development

php-cs-fix:
	./vendor/bin/php-cs-fixer fix --allow-risky yes --show-progress run-in

deploy:
	dep --parallel deploy

rollback-deploy:
	dep rollback

flush-cache:
	docker-compose exec redis redis-cli flushall

monitor-cache:
	docker-compose exec redis redis-cli monitor