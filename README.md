# install dependencies
composer install

# create .env file and generate the application key
cp .env.example .env   

php artisan key:generate  

php artisan config:cache   

php artisan migrate   

php artisan db:seed  


# launch the server
php artisan serve

# api endpoints
domain/api/?
