#!/bin/sh
set -e
cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app public/uploads

# Veritabani hazir olana kadar bekle (MySQL kullaniliyorsa)
if [ "$DB_CONNECTION" = "mysql" ]; then
    i=0
    until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT")?:3306).";dbname=".getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); } catch (Throwable $e) { exit(1); }'; do
        i=$((i+1))
        [ "$i" -ge 40 ] && { echo "Veritabanina baglanilamadi"; break; }
        echo "Veritabani bekleniyor..."
        sleep 3
    done
fi

# Tablolar + demo icerik + (yoksa) yonetici hesabi
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    php artisan portfolyo:kur --no-interaction \
        --ad="${ADMIN_NAME:-Yonetici}" --eposta="$ADMIN_EMAIL" --sifre="$ADMIN_PASSWORD" --demo="${DEMO_ICERIK:-1}" || true
else
    php artisan migrate --force || true
fi

chown -R www-data:www-data storage bootstrap/cache public/uploads .env 2>/dev/null || true

exec docker-php-entrypoint "$@"
