#!/bin/bash

chown -R www-data:www-data .
composer install --optimize-autoloader --no-dev
php artisan key:generate
#php artisan migrate

php-fpm