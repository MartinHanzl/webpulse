#!/bin/ash

php artisan migrate --force

php artisan optimize:clear
php artisan optimize

php artisan serve --host=0.0.0.0 --port=80
