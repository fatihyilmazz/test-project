#!/bin/bash
set -e

wait-for-it mysql:3306 -t 300

php artisan migrate
php artisan db:seed
php artisan passport:install

exec php-fpm &

while [ true ]
do
  php artisan schedule:run --verbose --no-interaction
  sleep 60
done
