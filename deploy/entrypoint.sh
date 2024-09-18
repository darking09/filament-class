#!/bin/sh

echo "pwd: $(pwd)"
echo "🎬 entrypoint.sh: [$(whoami)] [PHP $(php -r 'echo phpversion();')]"

composer dump-autoload

echo "🎬 artisan commands"

# 💡 Group into a custom command e.g. php artisan app:on-deploy
php artisan migrate --no-interaction --force
php artisan db:seed --no-interaction --force;

echo "🎬 storage link"

php artisan storage:link

echo "🎬 start supervisord"

php artisan queue:work --daemon &

supervisord -c $LARAVEL_PATH/deploy/config/supervisor.conf
