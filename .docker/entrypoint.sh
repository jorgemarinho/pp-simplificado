#!/bin/bash


if [ ! -f ".env" ]; then
    cp .env.example .env
fi

if [ ! -f ".env.testing" ]; then
    cp .env.testing.example .env.testing
fi

chown -R www-data:www-data .
composer install
php artisan key:generate

if [ "$1" = "--build" ]; then
    php artisan migrate
fi

php-fpm