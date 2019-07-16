<?php
return [
    'ignored_tables' => [
        'migrations',
        'password_resets',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        'theme',
        'social_login',
        //'users',
    ],
    'only_this_table' => [

    ],
    'only_table_with_prefix' => true,
    'table_prefix' => 'auth', // 'test',

    'without_pivot_tables' => true,

    'namespace_postfix' => 'Admin\Auth', // 'Test',
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

    'generate_routes' => true,

    'generate_providers' => true,
    'provider' => [
        'namespace' => '\app\Providers',
    ],

    'generate_middleware' => true,

    'middleware' => [
        'namespace' => '\app\Http\Middleware',
    ],



    'generate_views' => true,
    'view' => [
        'namespace' => 'resources\views',
    ],
    'ignored_columns_in_edit_create_views' => [
        'email_verification_token',
        'remember_token',
        'token',
        'signup_ip_address',
        'signup_confirmation_ip_address',
        'signup_sm_ip_address',
        'admin_ip_address',
        'updated_ip_address',
        'deleted_ip_address',
    ],
];
