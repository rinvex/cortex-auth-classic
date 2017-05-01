<?php

declare(strict_types=1);


Route::name('backend.')
     ->namespace('Cortex\Fort\Http\Controllers\Backend')
     ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
     ->prefix(config('rinvex.cortex.route.locale_prefix') ? '{locale}/backend' : 'backend')->group(function () {

     // Dashboard route
    Route::get('/')->name('home')->uses('DashboardController@home');

    // Abilities Routes
    Route::name('abilities.')->prefix('abilities')->group(function () {
        Route::get('/')->name('index')->uses('AbilitiesController@index');
        Route::get('create')->name('create')->uses('AbilitiesController@form');
        Route::post('create')->name('store')->uses('AbilitiesController@store');
        Route::get('{ability}')->name('edit')->uses('AbilitiesController@form')->where('ability', '[0-9]+');
        Route::put('{ability}')->name('update')->uses('AbilitiesController@update')->where('ability', '[0-9]+');
        Route::get('{ability}/logs')->name('logs')->uses('AbilitiesController@logs')->where('ability', '[0-9]+');
        Route::delete('{ability}')->name('delete')->uses('AbilitiesController@delete')->where('ability', '[0-9]+');
    });

    // Roles Routes
    Route::name('roles.')->prefix('roles')->group(function () {
        Route::get('/')->name('index')->uses('RolesController@index');
        Route::get('create')->name('create')->uses('RolesController@form');
        Route::post('create')->name('store')->uses('RolesController@store');
        Route::get('{role}')->name('edit')->uses('RolesController@form')->where('role', '[0-9]+');
        Route::put('{role}')->name('update')->uses('RolesController@update')->where('role', '[0-9]+');
        Route::get('{role}/logs')->name('logs')->uses('RolesController@logs')->where('role', '[0-9]+');
        Route::delete('{role}')->name('delete')->uses('RolesController@delete')->where('role', '[0-9]+');
    });

    // Users Routes
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/')->name('index')->uses('UsersController@index');
        Route::get('create')->name('create')->uses('UsersController@form');
        Route::post('create')->name('store')->uses('UsersController@store');
        Route::get('{user}')->name('edit')->uses('UsersController@form')->where('user', '[0-9]+');
        Route::put('{user}')->name('update')->uses('UsersController@update')->where('user', '[0-9]+');
        Route::get('{user}/logs')->name('logs')->uses('UsersController@logs')->where('user', '[0-9]+');
        Route::delete('{user}')->name('delete')->uses('UsersController@delete')->where('user', '[0-9]+');
    });
});
