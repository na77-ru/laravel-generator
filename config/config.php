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
    'only_with_table_prefix' => false,
    'table_prefix' => 'test', // 'test\\',

    'namespace_postfix' => '', // 'Test',
    'generate_model' => true,
    'model' => [
        'namespace' => '\app\Models',// '\app\Models',
    ],
    'generate_controller' => true,
    'controller' => [
        'namespace' => '\app\Http\Controllers',
    ],
    'generate_repository' => true,
    'repository' => [
        'namespace' => '\app\Repositories',
    ],
    'generate_observer' => true,
    'observer' => [
        'namespace' => '\app\Observers',
    ],
    'generate_request' => true,
    'request' => [
        'namespace' => '\app\Http\Requests',
    ],
    'generate_view' => false, // not generate yet
    'view' => [
        'namespace' => 'resources\views\\',
    ],

];
