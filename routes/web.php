<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Auth\Http\Controllers\Adminarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

        // Login Routes
             Route::get('login')->name('login')->uses('AuthenticationController@form');
             Route::post('login')->name('login.process')->uses('AuthenticationController@login');
             Route::post('logout')->name('logout')->uses('AuthenticationController@logout');

             // Reauthentication Routes
             Route::name('reauthentication.')->prefix('reauthentication')->group(function () {
                 // Reauthentication Password Routes
                 Route::post('password')->name('password.process')->uses('ReauthenticationController@processPassword');

                 // Reauthentication Twofactor Routes
                 Route::post('twofactor')->name('twofactor.process')->uses('ReauthenticationController@processTwofactor');
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

             Route::middleware(['can:access-adminarea'])->group(function () {

                 // Account Settings Route Alias
                 Route::get('account')->name('account')->uses('AccountSettingsController@index');

                 // User Account Routes
                 Route::name('account.')->prefix('account')->group(function () {
                     // Account Settings Routes
                     Route::get('settings')->name('settings')->uses('AccountSettingsController@edit');
                     Route::post('settings')->name('settings.update')->uses('AccountSettingsController@update');

                     // Account Password Routes
                     Route::get('password')->name('password')->uses('AccountPasswordController@edit');
                     Route::post('password')->name('password.update')->uses('AccountPasswordController@update');

                     // Account Attributes Routes
                     Route::get('attributes')->name('attributes')->uses('AccountAttributesController@edit');
                     Route::post('attributes')->name('attributes.update')->uses('AccountAttributesController@update');

                     // Account Sessions Routes
                     Route::get('sessions')->name('sessions')->uses('AccountSessionsController@index');
                     Route::delete('sessions')->name('sessions.flush')->uses('AccountSessionsController@flush');
                     Route::delete('sessions/{session?}')->name('sessions.destroy')->uses('AccountSessionsController@destroy');

                     // Account TwoFactor Routes
                     Route::name('twofactor.')->prefix('twofactor')->group(function () {
                         Route::get('/')->name('index')->uses('AccountTwoFactorController@index');

                         // Account TwoFactor TOTP Routes
                         Route::name('totp.')->prefix('totp')->group(function () {
                             Route::get('enable')->name('enable')->uses('AccountTwoFactorController@enableTotp');
                             Route::post('update')->name('update')->uses('AccountTwoFactorController@updateTotp');
                             Route::post('disable')->name('disable')->uses('AccountTwoFactorController@disableTotp');
                             Route::post('backup')->name('backup')->uses('AccountTwoFactorController@backupTotp');
                         });

                         // Account TwoFactor Phone Routes
                         Route::name('phone.')->prefix('phone')->group(function () {
                             Route::post('enable')->name('enable')->uses('AccountTwoFactorController@enablePhone');
                             Route::post('disable')->name('disable')->uses('AccountTwoFactorController@disablePhone');
                         });
                     });
                 });

                 // Abilities Routes
                 Route::name('abilities.')->prefix('abilities')->group(function () {
                     Route::get('/')->name('index')->uses('AbilitiesController@index');
                     Route::get('create')->name('create')->uses('AbilitiesController@create');
                     Route::post('create')->name('store')->uses('AbilitiesController@store');
                     Route::get('{ability}')->name('edit')->uses('AbilitiesController@edit');
                     Route::put('{ability}')->name('update')->uses('AbilitiesController@update');
                     Route::get('{ability}/logs')->name('logs')->uses('AbilitiesController@logs');
                     Route::delete('{ability}')->name('destroy')->uses('AbilitiesController@destroy');
                 });

                 // Roles Routes
                 Route::name('roles.')->prefix('roles')->group(function () {
                     Route::get('/')->name('index')->uses('RolesController@index');
                     Route::get('create')->name('create')->uses('RolesController@create');
                     Route::post('create')->name('store')->uses('RolesController@store');
                     Route::get('{role}')->name('edit')->uses('RolesController@edit');
                     Route::put('{role}')->name('update')->uses('RolesController@update');
                     Route::get('{role}/logs')->name('logs')->uses('RolesController@logs');
                     Route::delete('{role}')->name('destroy')->uses('RolesController@destroy');
                 });

                 // Admins Routes
                 Route::name('admins.')->prefix('admins')->group(function () {
                     Route::get('/')->name('index')->uses('AdminsController@index');
                     Route::get('create')->name('create')->uses('AdminsController@create');
                     Route::post('create')->name('store')->uses('AdminsController@store');
                     Route::get('{admin}')->name('edit')->uses('AdminsController@edit');
                     Route::put('{admin}')->name('update')->uses('AdminsController@update');
                     Route::get('{admin}/logs')->name('logs')->uses('AdminsController@logs');
                     Route::get('{admin}/activities')->name('activities')->uses('AdminsController@activities');
                     Route::get('{admin}/attributes')->name('attributes')->uses('AdminsController@attributes');
                     Route::put('{admin}/attributes')->name('attributes.update')->uses('AdminsController@updateAttributes');
                     Route::delete('{admin}')->name('destroy')->uses('AdminsController@destroy');
                     Route::delete('{admin}/media/{media}')->name('media.destroy')->uses('AdminsMediaController@destroy');
                 });

                 // Guardians Routes
                 Route::name('guardians.')->prefix('guardians')->group(function () {
                     Route::get('/')->name('index')->uses('GuardiansController@index');
                     Route::get('create')->name('create')->uses('GuardiansController@create');
                     Route::post('create')->name('store')->uses('GuardiansController@store');
                     Route::get('{guardian}')->name('edit')->uses('GuardiansController@edit');
                     Route::put('{guardian}')->name('update')->uses('GuardiansController@update');
                     Route::get('{guardian}/logs')->name('logs')->uses('GuardiansController@logs');
                     Route::delete('{guardian}')->name('destroy')->uses('GuardiansController@destroy');
                 });
             });
         });
});
