<?php

//Route::get('/contact', function(){
//    return 'Hello from the contact form package';
//});
Route::get('/generator_contact', function(){
    return view('generator-contact-form::contact');
})->name('generator_contact');

Route::get('/alex-claimer-generate',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@generate')
    ->name('alex-claimer-generate');

Route::get('/generator_create',
    '\AlexClaimer\Generator\App\Http\Controllers\GeneratorController@create')
    ->name('generator_create');

