composer install --prefer-dist --no-dev -o
npm install
npm run build
php artisan filament:clear-cached-components
php artisan optimize:clear
php artisan queue:restart
php artisan optimize
php artisan route:cache
php artisan config:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:cache-components
