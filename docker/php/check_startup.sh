#!bin/bash

cd /var/www/html

composer update

cp .env.example .env

sed -i -e 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env
sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=root/g' .env

php artisan key:generate
php artisan storage:link

chown www-data storage/ -R

php artisan cache:clear