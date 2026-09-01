#!/bin/sh
set -e

# Clear stale dev package manifest if mounted from host
rm -f /var/www/html/bootstrap/cache/packages.php /var/www/html/bootstrap/cache/services.php

# Generate app key if missing
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
fi

# Ensure storage link exists
php artisan storage:link --force --quiet || true

# Wait for DB if needed & run migrations safely
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction
fi

# Cache config & routes for production speed if enabled
if [ "$APP_ENV" = "production" ] && [ "$APP_CONFIG_CACHE" = "true" ]; then
    echo "Caching configuration & routes..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
