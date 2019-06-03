Not stable yet.
Generate classes: Models, Controllers(empty yet), Repositories, Requests, Observers and views(not yet)
from DB tables.

Usage

composer require alex-claimer/generator --dev

php artisan vendor:publish --tag=alex-claimer-generator-config

set in config/alex-claimer-generator/config.php





url: Your_project/generator_create_migration 

or

php artisan generate:migration {{parameters}}  -  generate migration 

{{parameters}}  -  {
posts - create migration for 'posts' table;

posts_comments - create migrations for 'posts' and 'comments' tables;

posts__comments - create migrations for 'posts' , 'comments' and
pivot 'link_post_comments' tables;


posts___comments - create migration only for pivot 'link_post_comments' table;



posts_prefix_test - create migration for 'test_posts' table;

posts__comments_prefix_test - create migrations for 'test_posts' , 'test_comments' and
                              pivot 'test_link_post_comments' tables;
                                                            
pivot with columns  'post_id'     on   'test_posts' 

                    'comment_id'     on   'test_comments'                      

}
php artisan migrate

php artisan generate:classes  - generate classes from DB


or

url: Your_project/generator_menu

Generated classes is in config/alex-claimer-generator/already_made.php

If you need to generate class again, remove it from
config/alex-claimer-generator/already_made.php




