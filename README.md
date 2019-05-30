Usage

composer require alex-claimer/generator --dev

php artisan vendor:publish --tag=alex-claimer-generator-config

composer dump-autoload

set in config/alex-claimer-generator/config.php

php artisan gen:classes
or
url: your_project/alex-claimer-generate

Generated classes is in config/alex-claimer-generator/already_made.php

If you need to generate class again, remove it from
config/alex-claimer-generator/already_made.php




