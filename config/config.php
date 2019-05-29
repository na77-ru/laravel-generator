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
    'table_prefix' => 'test',

    'namespace_postfix' => '', // 'Test\\',

    'model' => [
        'namespace' => '\app\Models\\',// '\app\Models\\',
    ],
    'controller' => [
        'namespace' => '\app\Http\Controllers\\',
    ],
    'repository' => [
        'namespace' => '\app\Repositories\\',
    ],
    'observer' => [
        'namespace' => '\app\Observers\\',
    ],
    'request' => [
        'namespace' => '\app\Http\Requests\\',
    ],
    'view' => [
        'namespace' => 'resources\views\\',
    ],

];
