# Commmands executed as entrypoint once the container is running.
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console 
exec apache2-foreground