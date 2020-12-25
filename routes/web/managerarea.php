<?php

declare(strict_types=1);

Route::domain('{subdomain}.'.domain())->group(function () {
    Route::name('managerarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Auth\Http\Controllers\Managerarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.managerarea') : config('cortex.foundation.route.prefix.managerarea'))->group(function () {
             Route::name('cortex.auth.account.')->group(function () {

                // Login Routes
                 Route::get('login')->name('login')->uses('AuthenticationController@form');
                 Route::post('login')->name('login.process')->uses('AuthenticationController@login');
                 Route::post('logout')->name('logout')->uses('AuthenticationController@logout');

                 // Social Authentication Routes
                 Route::redirect('auth', 'login')->name('auth');
                 Route::get('auth/{provider}')->name('auth.social')->uses('SocialAuthenticationController@redirectToProvider');
                 Route::get('auth/{provider}/callback')->name('auth.social.callback')->uses('SocialAuthenticationController@handleProviderCallback');

                 // Reauthentication Routes: Password & Twofactor
                 Route::name('reauthentication.')->prefix('reauthentication')->group(function () {
                     Route::post('password')->name('password.process')->uses('ReauthenticationController@processPassword');
                     Route::post('twofactor')->name('twofactor.process')->uses('ReauthenticationController@processTwofactor');
                 });

                 // Password Reset Routes
                 Route::get('passwordreset')->name('passwordreset')->uses('RedirectionController@passwordreset');
                 Route::name('passwordreset.')->prefix('passwordreset')->group(function () {
                     Route::get('request')->name('request')->uses('PasswordResetController@request');
                     Route::post('send')->name('send')->uses('PasswordResetController@send');
                     Route::get('reset')->name('reset')->uses('PasswordResetController@reset');
                     Route::post('process')->name('process')->uses('PasswordResetController@process');
                 });

                 // Verification Routes
                 Route::get('verification')->name('verification')->uses('RedirectionController@verification');
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

             Route::middleware(['can:access-managerarea'])->group(function () {

                 // Account Settings Route Alias
                 Route::get('account')->name('cortex.auth.account')->uses('AccountSettingsController@index');

                 // User Account Routes
                 Route::name('cortex.auth.account.')->prefix('account')->group(function () {
                     // Account Settings Routes
                     Route::get('settings')->name('settings')->uses('AccountSettingsController@edit');
                     Route::post('settings')->name('settings.update')->uses('AccountSettingsController@update');

                     // Account Media Routes
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses('AccountMediaController@destroy');

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
                     Route::get('twofactor')->name('twofactor')->uses('AccountTwoFactorController@index');
                     Route::name('twofactor.')->prefix('twofactor')->group(function () {

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

                 // Roles Routes
                 Route::name('cortex.auth.roles.')->prefix('roles')->group(function () {
                     Route::get('/')->name('index')->uses('RolesController@index');
                     Route::get('import')->name('import')->uses('RolesController@import');
                     Route::post('import')->name('stash')->uses('RolesController@stash');
                     Route::post('hoard')->name('hoard')->uses('RolesController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('RolesController@importLogs');
                     Route::get('create')->name('create')->uses('RolesController@create');
                     Route::post('create')->name('store')->uses('RolesController@store');
                     Route::get('{role}')->name('show')->uses('RolesController@show');
                     Route::get('{role}/edit')->name('edit')->uses('RolesController@edit');
                     Route::put('{role}/edit')->name('update')->uses('RolesController@update');
                     Route::get('{role}/logs')->name('logs')->uses('RolesController@logs');
                     Route::delete('{role}')->name('destroy')->uses('RolesController@destroy');
                 });

                 // Members Routes
                 Route::name('cortex.auth.members.')->prefix('members')->group(function () {
                     Route::get('/')->name('index')->uses('MembersController@index');
                     Route::get('import')->name('import')->uses('MembersController@import');
                     Route::post('import')->name('stash')->uses('MembersController@stash');
                     Route::post('hoard')->name('hoard')->uses('MembersController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('MembersController@importLogs');
                     Route::get('create')->name('create')->uses('MembersController@create');
                     Route::post('create')->name('store')->uses('MembersController@store');
                     Route::get('{member}')->name('show')->uses('MembersController@show');
                     Route::get('{member}/edit')->name('edit')->uses('MembersController@edit');
                     Route::put('{member}/edit')->name('update')->uses('MembersController@update');
                     Route::get('{member}/logs')->name('logs')->uses('MembersController@logs');
                     Route::get('{member}/activities')->name('activities')->uses('MembersController@activities');
                     Route::get('{member}/attributes')->name('attributes')->uses('MembersController@attributes');
                     Route::put('{member}/attributes')->name('attributes.update')->uses('MembersController@updateAttributes');
                     Route::delete('{member}')->name('destroy')->uses('MembersController@destroy');
                     Route::delete('{member}/media/{media}')->name('media.destroy')->uses('MembersMediaController@destroy');
                 });

                 // Managers Routes
                 Route::name('cortex.auth.managers.')->prefix('managers')->group(function () {
                     Route::get('/')->name('index')->uses('ManagersController@index');
                     Route::get('import')->name('import')->uses('ManagersController@import');
                     Route::post('import')->name('stash')->uses('ManagersController@stash');
                     Route::post('hoard')->name('hoard')->uses('ManagersController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('ManagersController@importLogs');
                     Route::get('create')->name('create')->uses('ManagersController@create');
                     Route::post('create')->name('store')->uses('ManagersController@store');
                     Route::get('{manager}')->name('show')->uses('ManagersController@show');
                     Route::get('{manager}/edit')->name('edit')->uses('ManagersController@edit');
                     Route::put('{manager}/edit')->name('update')->uses('ManagersController@update');
                     Route::get('{manager}/logs')->name('logs')->uses('ManagersController@logs');
                     Route::get('{manager}/activities')->name('activities')->uses('ManagersController@activities');
                     Route::get('{manager}/attributes')->name('attributes')->uses('ManagersController@attributes');
                     Route::put('{manager}/attributes')->name('attributes.update')->uses('ManagersController@updateAttributes');
                     Route::delete('{manager}')->name('destroy')->uses('ManagersController@destroy');
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses('ManagersMediaController@destroy');
                 });
             });
         });
});
