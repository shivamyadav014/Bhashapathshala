#!/usr/bin/env bash
set -e

php artisan config:clear
php artisan view:clear

php artisan storage:link || true
php artisan migrate --force

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache

apache2-foreground
