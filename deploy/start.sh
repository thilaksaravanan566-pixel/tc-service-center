#!/bin/sh
set -e

echo "==> Starting PHP-FPM..."
php-fpm -D

echo "==> Waiting for PHP-FPM socket to be ready..."
sleep 2

echo "==> Running Laravel migrations..."
php artisan migrate --force

echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache

# view:cache is intentionally skipped — blade errors crash the container.
# Cache it manually after confirming no view errors.

echo "==> Creating storage symlink..."
php artisan storage:link || true

echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Starting Nginx..."
exec nginx -g "daemon off;"
