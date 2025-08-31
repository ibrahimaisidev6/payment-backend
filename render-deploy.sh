#!/bin/bash
echo "Démarrage du déploiement..."
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Optimisations terminées"
