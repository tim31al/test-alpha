#!/bin/bash

docker-compose build frontend

docker-compose up -d

docker-compose exec app composer install
docker-compose exec -u app app php bin/console doctrine:migrations:migrate -q
docker-compose exec -u app app php bin/console doctrine:fixtures:load --group AppFixtures -q
