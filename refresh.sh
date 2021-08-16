
#php71-cli artisan make:controller PagesController --resource


php71-cli artisan migrate:refresh

read -p "Naciśnij enter by kontunuować"

php71-cli artisan db:seed
