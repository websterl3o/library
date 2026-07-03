#!/bin/bash
set -e

echo "Starting Library Manager Setup..."

# Run docker compose from the root directory
# Build and start containers
docker compose up -d --build

echo "Installing PHP dependencies..."
docker compose exec app composer install

echo "Installing NPM dependencies..."
docker compose exec app npm install

echo "Building frontend assets..."
# (This will fail initially if package.json doesn't have build script yet, but we add || true)
docker compose exec app npm run build || true

echo "Generating application key..."
docker compose exec app php artisan key:generate --no-interaction

echo "Running migrations (if any)..."
docker compose exec app php artisan migrate --force || true

echo "Setup Complete! Access the application at http://localhost:8000"
