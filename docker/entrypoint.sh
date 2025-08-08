#!/bin/bash
set -e

echo "Running Laravel setup automation..."

# 1. Copy env if not exists
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo ".env file created."
fi

# 2. Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate app key (only if not set)
php artisan key:generate
echo "App key generated."



php artisan key:generate --env=testing
echo "Testing env key generated."


# 4. Run migrations & seed
php artisan migrate --seed
php artisan migrate --env=testing

# 5. JWT secrets (only if not set)
php artisan jwt:secret --force
echo "JWT secret generated."


php artisan jwt:secret --env=testing --force
echo "JWT secret generated for testing."

# 6. Storage link
php artisan storage:link --force|| true

php artisan test

echo "Laravel setup complete!"

exec "$@"
