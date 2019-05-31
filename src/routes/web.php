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

