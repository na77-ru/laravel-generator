<?php
return [
    'ignored_tables' => [
        'migrations',
        'password_resets',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        //'users',
    ],
    'only_this_table' => [

    ],
    'only_table_with_prefix' => false,
    'table_prefix' => 'test', // 'test',

    'namespace_postfix' => '', // 'Test',
    'generate_models' => true,
    'model' => [
        'namespace' => '\app\Models',// '\app\Models',
    ],
    'generate_controllers' => true, //  generate empty yet
    'controller' => [
        'namespace' => '\app\Http\Controllers',
    ],
    'generate_repositories' => true,
    'repository' => [
        'namespace' => '\app\Repositories',
    ],
    'generate_observers' => true,
    'observer' => [
        'namespace' => '\app\Observers',
    ],
    'generate_requests' => true,
    'request' => [
        'namespace' => '\app\Http\Requests',
    ],
    'generate_views' => false, // not generate yet
    'view' => [
        'namespace' => 'resources\views',
    ],

];
