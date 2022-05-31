#!/bin/bash
# init.sh

set -e

docker-compose up -d db

echo "Build frontend"
docker-compose build frontend

docker-compose up -d
echo "Wakeup services"
sleep 10

echo "Install dependencies"
docker-compose exec app composer install

echo "Migrate"
docker-compose exec -u app app php bin/console doctrine:migrations:migrate -q

echo "Load fixtures"
docker-compose exec -u app app php bin/console doctrine:fixtures:load --group AppFixtures -q
