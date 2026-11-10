#!/bin/sh
set -eu

public_storage_path="${PUBLIC_STORAGE_PATH:-/var/data/public}"
port="${PORT:-10000}"

mkdir -p "$public_storage_path"
chown www-data:www-data "$public_storage_path"

php artisan storage:link
php artisan migrate --force

sed -i "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
