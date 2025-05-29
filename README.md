Okay so ti run the project its pretty easy there are two ways 
1. Just install on an apache or ngix server

When the application is running just run the command `php artisan movies:simple-import --pages=5` and boom you have imported the most popular movies and also run `php artisan migrate --seed` to create the demo user that is articulated in your documentation