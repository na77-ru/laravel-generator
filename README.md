Not stable yet.
Generate classes: Models, Controllers(empty yet), Repositories, Requests, Observers and views(not yet)
from DB tables.

Usage

composer require alex-claimer/generator --dev

php artisan vendor:publish --tag=alex-claimer-generator-config

set in config/alex-claimer-generator/config.php

php artisan generate:classes  - generate classes from DB
php artisan generate:migration - generate migration (not yet parameters)

or

url: Your_project/generator_menu

Generated classes is in config/alex-claimer-generator/already_made.php

If you need to generate class again, remove it from
config/alex-claimer-generator/already_made.php




