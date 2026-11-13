#!/bin/sh
set -eu
: "${APP_KEY:?Set a permanent Laravel APP_KEY in Render}"
: "${ADMIN_PASSWORD:?Set ADMIN_PASSWORD in Render}"
: "${DB_DATABASE:?Set the absolute SQLite database path}"
mkdir -p "$(dirname "$DB_DATABASE")" storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
# Create the database only on first boot; never overwrite existing data.
if [ ! -f "$DB_DATABASE" ]; then touch "$DB_DATABASE"; fi
chown -R www-data:www-data storage "$(dirname "$DB_DATABASE")" bootstrap/cache
php artisan config:cache
php artisan migrate --force
php artisan storage:link --force
php artisan view:cache
exec apache2-foreground
