#!/usr/bin/env bash
set -e

cd /var/www/html

echo "Installing PHP dependencies..."
composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

echo "Preparing writable Laravel directories..."
mkdir -p bootstrap/cache \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs
chmod -R ug+rwX bootstrap/cache storage

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is missing. Generating a deploy-time Laravel key..."
    touch .env
    php artisan key:generate --force
fi

echo "Preparing Laravel caches..."
php artisan config:clear
php artisan view:clear
php artisan storage:link || true

echo "Running database migrations..."
php artisan migrate --force

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
fi

php artisan config:cache
