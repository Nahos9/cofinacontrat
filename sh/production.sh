rm recrutement.zip;
wget https://42ba-156-38-73-89.ngrok-free.app/recrutement.zip;
unzip -o recrutement.zip;
cp mv .env-prod .env;
composer install;
php artisan storage:link;
php artisan migrate:refresh --seed;
