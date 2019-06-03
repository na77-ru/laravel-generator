<?php



Route::get('/generator_menu',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@generator_menu')
    ->name('generator_menu');

Route::get('/show_alex_claimer_generator_config',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@show_alex_claimer_generator_config')
->name('show_alex_claimer_generator_config');


Route::patch('/alex-claimer-generate-patch',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@generate_patch')
    ->name('alex-claimer-generate-patch');

Route::get('/generator_create',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@create')
    ->name('generator_create');

//MIGRATION
Route::get('/generator_create_migration',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@menu_create_migration')
    ->name('generator_create_migration');

Route::patch('/generator_store_migration',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@store_migration')
    ->name('generator_store_migration');

//SEEDERS
Route::get('/generator_create_seeders',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@menu_create_seeders')
    ->name('generator_create_seeders');

Route::patch('/generator_store_seeders',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@store_seeders')
    ->name('generator_store_seeders');

