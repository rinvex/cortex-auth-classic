<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('guestarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Fort\Http\Controllers\Guestarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}' : '')->group(function () {

            // Authentication Routes
             Route::name('auth.')->prefix('auth')->group(function () {
                 // Login Routes
                 Route::get('login')->name('login')->uses('AuthenticationController@form');
                 Route::post('login')->name('login.process')->uses('AuthenticationController@login');
                 Route::post('logout')->name('logout')->uses('AuthenticationController@logout');

                 // Registration Routes
                 Route::get('register')->name('register')->uses('RegistrationController@form');
                 Route::post('register')->name('register.process')->uses('RegistrationController@register');

                 // Social Authentication Routes
                 Route::get('github')->name('social.github')->uses('SocialAuthenticationController@redirectToGithub');
                 Route::get('github/callback')->name('social.github.callback')->uses('SocialAuthenticationController@handleGithubCallback');
             });

             // Password Reset Routes
             Route::name('passwordreset.')->prefix('passwordreset')->group(function () {
                 Route::get('request')->name('request')->uses('PasswordResetController@request');
                 Route::post('send')->name('send')->uses('PasswordResetController@send');
                 Route::get('reset')->name('reset')->uses('PasswordResetController@reset');
                 Route::post('process')->name('process')->uses('PasswordResetController@process');
             });

             // Verification Routes
             Route::name('verification.')->prefix('verification')->group(function () {
                 // Phone Verification Routes
                 Route::name('phone.')->prefix('phone')->group(function () {
                     Route::get('request')->name('request')->uses('PhoneVerificationController@request');
                     Route::post('send')->name('send')->uses('PhoneVerificationController@send');
                     Route::get('verify')->name('verify')->uses('PhoneVerificationController@verify');
                     Route::post('process')->name('process')->uses('PhoneVerificationController@process');
                 });

                 // Email Verification Routes
                 Route::name('email.')->prefix('email')->group(function () {
                     Route::get('request')->name('request')->uses('EmailVerificationController@request');
                     Route::post('send')->name('send')->uses('EmailVerificationController@send');
                     Route::get('verify')->name('verify')->uses('EmailVerificationController@verify');
                 });
             });
         });


    Route::name('memberarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Fort\Http\Controllers\Memberarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.memberarea') : config('cortex.foundation.route.prefix.memberarea'))->group(function () {

             // User Account Routes
             Route::name('account.')->prefix('account')->group(function () {
                 // Account Page Routes
                 Route::get('settings')->name('settings')->uses('AccountSettingsController@edit');
                 Route::post('settings')->name('settings.update')->uses('AccountSettingsController@update');
	
	             // Password Page Routes
	             Route::get('password')->name('password')->uses('AccountPasswordController@edit');
	             Route::post('password')->name('password.update')->uses('AccountPasswordController@update');
	
	             // Sessions Manipulation Routes
                 Route::get('sessions')->name('sessions')->uses('AccountSessionsController@index');
                 Route::delete('sessions/{token?}')->name('sessions.flush')->uses('AccountSessionsController@flush');

                 // TwoFactor Authentication Routes
                 Route::name('twofactor.')->prefix('twofactor')->group(function () {
                     // TwoFactor TOTP Routes
                     Route::name('totp.')->prefix('totp')->group(function () {
                         Route::get('enable')->name('enable')->uses('TwoFactorSettingsController@enableTotp');
                         Route::post('update')->name('update')->uses('TwoFactorSettingsController@updateTotp');
                         Route::post('disable')->name('disable')->uses('TwoFactorSettingsController@disableTotp');
                         Route::post('backup')->name('backup')->uses('TwoFactorSettingsController@backupTotp');
                     });

                     // TwoFactor Phone Routes
                     Route::name('phone.')->prefix('phone')->group(function () {
                         Route::post('enable')->name('enable')->uses('TwoFactorSettingsController@enablePhone');
                         Route::post('disable')->name('disable')->uses('TwoFactorSettingsController@disablePhone');
                     });
                 });
             });
         });


    Route::name('adminarea.')
         ->namespace('Cortex\Fort\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

            // Abilities Routes
             Route::name('abilities.')->prefix('abilities')->group(function () {
                 Route::get('/')->name('index')->uses('AbilitiesController@index');
                 Route::get('create')->name('create')->uses('AbilitiesController@form');
                 Route::post('create')->name('store')->uses('AbilitiesController@store');
                 Route::get('{ability}')->name('edit')->uses('AbilitiesController@form');
                 Route::put('{ability}')->name('update')->uses('AbilitiesController@update');
                 Route::get('{ability}/logs')->name('logs')->uses('AbilitiesController@logs');
                 Route::delete('{ability}')->name('delete')->uses('AbilitiesController@delete');
             });

             // Roles Routes
             Route::name('roles.')->prefix('roles')->group(function () {
                 Route::get('/')->name('index')->uses('RolesController@index');
                 Route::get('create')->name('create')->uses('RolesController@form');
                 Route::post('create')->name('store')->uses('RolesController@store');
                 Route::get('{role}')->name('edit')->uses('RolesController@form');
                 Route::put('{role}')->name('update')->uses('RolesController@update');
                 Route::get('{role}/logs')->name('logs')->uses('RolesController@logs');
                 Route::delete('{role}')->name('delete')->uses('RolesController@delete');
             });

             // Users Routes
             Route::name('users.')->prefix('users')->group(function () {
                 Route::get('/')->name('index')->uses('UsersController@index');
                 Route::get('create')->name('create')->uses('UsersController@form');
                 Route::post('create')->name('store')->uses('UsersController@store');
                 Route::get('{user}')->name('edit')->uses('UsersController@form');
                 Route::put('{user}')->name('update')->uses('UsersController@update');
                 Route::get('{user}/logs')->name('logs')->uses('UsersController@logs');
                 Route::get('{user}/activities')->name('activities')->uses('UsersController@activities');
                 Route::delete('{user}')->name('delete')->uses('UsersController@delete');
             });
         });
});


Route::domain('{subdomain}.'.domain())->group(function () {

    Route::name('tenantarea.')
         ->namespace('Cortex\Fort\Http\Controllers\Tenantarea')
         ->middleware(['web', 'nohttpcache', 'can:access-tenantarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.tenantarea') : config('cortex.foundation.route.prefix.tenantarea'))->group(function () {

             // Roles Routes
             Route::name('roles.')->prefix('roles')->group(function () {
                 Route::get('/')->name('index')->uses('RolesController@index');
                 Route::get('create')->name('create')->uses('RolesController@form');
                 Route::post('create')->name('store')->uses('RolesController@store');
                 Route::get('{role}')->name('edit')->uses('RolesController@form');
                 Route::put('{role}')->name('update')->uses('RolesController@update');
                 Route::get('{role}/logs')->name('logs')->uses('RolesController@logs');
                 Route::delete('{role}')->name('delete')->uses('RolesController@delete');
             });

             // Users Routes
             Route::name('users.')->prefix('users')->group(function () {
                 Route::get('/')->name('index')->uses('UsersController@index');
                 Route::get('create')->name('create')->uses('UsersController@form');
                 Route::post('create')->name('store')->uses('UsersController@store');
                 Route::get('{user}')->name('edit')->uses('UsersController@form');
                 Route::put('{user}')->name('update')->uses('UsersController@update');
                 Route::get('{user}/logs')->name('logs')->uses('UsersController@logs');
                 Route::get('{user}/activities')->name('activities')->uses('UsersController@activities');
                 Route::delete('{user}')->name('delete')->uses('UsersController@delete');
             });
         });
});
