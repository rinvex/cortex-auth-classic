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

             Route::middleware(['can:access-adminarea'])->group(function () {

                 // Account Settings Route Alias
                 Route::get('account')->name('account')->uses('AccountSettingsController@index');

                 // User Account Routes
                 Route::name('account.')->prefix('account')->group(function () {
                     // Account Settings Routes
                     Route::get('settings')->name('settings')->uses('AccountSettingsController@edit');
                     Route::post('settings')->name('settings.update')->uses('AccountSettingsController@update');

                     // Account Media Routes
                     Route::delete('{member}/media/{media}')->name('media.destroy')->uses('AccountMediaController@destroy');

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

                 // Abilities Routes
                 Route::name('abilities.')->prefix('abilities')->group(function () {
                     Route::get('/')->name('index')->uses('AbilitiesController@index');
                     Route::get('import')->name('import')->uses('AbilitiesController@import');
                     Route::post('import')->name('stash')->uses('AbilitiesController@stash');
                     Route::post('hoard')->name('hoard')->uses('AbilitiesController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('AbilitiesController@importLogs');
                     Route::get('create')->name('create')->uses('AbilitiesController@create');
                     Route::post('create')->name('store')->uses('AbilitiesController@store');
                     Route::get('{ability}')->name('show')->uses('AbilitiesController@show');
                     Route::get('{ability}/edit')->name('edit')->uses('AbilitiesController@edit');
                     Route::put('{ability}/edit')->name('update')->uses('AbilitiesController@update');
                     Route::get('{ability}/logs')->name('logs')->uses('AbilitiesController@logs');
                     Route::delete('{ability}')->name('destroy')->uses('AbilitiesController@destroy');
                     Route::post('bulk-delete')->name('bulk-delete')->uses('AbilitiesController@bulkDelete');
                 });

                 // Roles Routes
                 Route::name('roles.')->prefix('roles')->group(function () {
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
                     Route::post('bulk-delete')->name('bulk-delete')->uses('RolesController@bulkDelete');
                 });

                 // Admins Routes
                 Route::name('admins.')->prefix('admins')->group(function () {
                     Route::get('/')->name('index')->uses('AdminsController@index');
                     Route::get('import')->name('import')->uses('AdminsController@import');
                     Route::post('import')->name('stash')->uses('AdminsController@stash');
                     Route::post('hoard')->name('hoard')->uses('AdminsController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('AdminsController@importLogs');
                     Route::get('create')->name('create')->uses('AdminsController@create');
                     Route::post('create')->name('store')->uses('AdminsController@store');
                     Route::get('{admin}')->name('show')->uses('AdminsController@show');
                     Route::get('{admin}/edit')->name('edit')->uses('AdminsController@edit');
                     Route::put('{admin}/edit')->name('update')->uses('AdminsController@update');
                     Route::get('{admin}/logs')->name('logs')->uses('AdminsController@logs');
                     Route::get('{admin}/activities')->name('activities')->uses('AdminsController@activities');
                     Route::get('{admin}/attributes')->name('attributes')->uses('AdminsController@attributes');
                     Route::put('{admin}/attributes')->name('attributes.update')->uses('AdminsController@updateAttributes');
                     Route::delete('{admin}')->name('destroy')->uses('AdminsController@destroy');
                     Route::delete('{admin}/media/{media}')->name('media.destroy')->uses('AdminsMediaController@destroy');
                     Route::post('bulk-delete')->name('bulk-delete')->uses('AdminsMediaController@bulkDelete');
                     Route::post('bulk-activate')->name('bulk-activate')->uses('AdminsMediaController@bulkActivate');
                     Route::post('bulk-deactivate')->name('bulk-deactivate')->uses('AdminsMediaController@bulkDeactivate');
                 });

                 // Managers Routes
                 Route::name('managers.')->prefix('managers')->group(function () {
                     Route::get('/')->name('index')->uses('ManagersController@index');
                     Route::get('import')->name('import')->uses('ManagersController@import');
                     Route::post('import')->name('stash')->uses('ManagersController@stash');
                     Route::post('hoard')->name('hoard')->uses('ManagersController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('ManagersController@importLogs');
                     Route::get('create')->name('create')->uses('ManagersController@form');
                     Route::post('create')->name('store')->uses('ManagersController@store');
                     Route::get('{manager}')->name('show')->uses('ManagersController@show');
                     Route::get('{manager}/edit')->name('edit')->uses('ManagersController@form');
                     Route::put('{manager}/edit')->name('update')->uses('ManagersController@update');
                     Route::get('{manager}/logs')->name('logs')->uses('ManagersController@logs');
                     Route::get('{manager}/activities')->name('activities')->uses('ManagersController@activities');
                     Route::get('{manager}/attributes')->name('attributes')->uses('ManagersController@attributes');
                     Route::put('{manager}/attributes')->name('attributes.update')->uses('ManagersController@updateAttributes');
                     Route::delete('{manager}')->name('destroy')->uses('ManagersController@destroy');
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses('ManagersMediaController@destroy');
                     Route::post('bulk-delete')->name('bulk-delete')->uses('ManagersController@bulkDelete');
                     Route::post('bulk-activate')->name('bulk-activate')->uses('ManagersController@bulkActivate');
                     Route::post('bulk-deactivate')->name('bulk-deactivate')->uses('ManagersController@bulkDeactivate');
                 });

                 // Members Routes
                 Route::name('members.')->prefix('members')->group(function () {
                     Route::get('/')->name('index')->uses('MembersController@index');
                     Route::post('ajax')->name('ajax')->uses('MembersController@ajax'); // @TODO: to be refactored!
                     Route::get('import')->name('import')->uses('MembersController@import');
                     Route::post('import')->name('stash')->uses('MembersController@stash');
                     Route::post('hoard')->name('hoard')->uses('MembersController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('MembersController@importLogs');
                     Route::get('create')->name('create')->uses('MembersController@form');
                     Route::post('create')->name('store')->uses('MembersController@store');
                     Route::get('{member}')->name('show')->uses('MembersController@show');
                     Route::get('{member}/edit')->name('edit')->uses('MembersController@form');
                     Route::put('{member}/edit')->name('update')->uses('MembersController@update');
                     Route::get('{member}/logs')->name('logs')->uses('MembersController@logs');
                     Route::get('{member}/activities')->name('activities')->uses('MembersController@activities');
                     Route::get('{member}/attributes')->name('attributes')->uses('MembersController@attributes');
                     Route::put('{member}/attributes')->name('attributes.update')->uses('MembersController@updateAttributes');
                     Route::delete('{member}')->name('destroy')->uses('MembersController@destroy');
                     Route::delete('{member}/media/{media}')->name('media.destroy')->uses('MembersMediaController@destroy');
                     Route::post('bulk-delete')->name('bulk-delete')->uses('MembersMediaController@bulkDelete');
                     Route::post('bulk-activate')->name('bulk-activate')->uses('MembersMediaController@bulkActivate');
                     Route::post('bulk-deactivate')->name('bulk-deactivate')->uses('MembersMediaController@bulkDeactivate');
                 });

                 // Guardians Routes
                 Route::name('guardians.')->prefix('guardians')->group(function () {
                     Route::get('/')->name('index')->uses('GuardiansController@index');
                     Route::get('import')->name('import')->uses('GuardiansController@import');
                     Route::post('import')->name('stash')->uses('GuardiansController@stash');
                     Route::post('hoard')->name('hoard')->uses('GuardiansController@hoard');
                     Route::get('import/logs')->name('import.logs')->uses('GuardiansController@importLogs');
                     Route::get('create')->name('create')->uses('GuardiansController@form');
                     Route::post('create')->name('store')->uses('GuardiansController@store');
                     Route::get('{guardian}')->name('show')->uses('GuardiansController@show');
                     Route::get('{guardian}/edit')->name('edit')->uses('GuardiansController@form');
                     Route::put('{guardian}/edit')->name('update')->uses('GuardiansController@update');
                     Route::get('{guardian}/logs')->name('logs')->uses('GuardiansController@logs');
                     Route::delete('{guardian}')->name('destroy')->uses('GuardiansController@destroy');
                     Route::post('bulk-delete')->name('bulk-delete')->uses('GuardiansController@bulkDelete');
                     Route::post('bulk-activate')->name('bulk-activate')->uses('GuardiansController@bulkActivate');
                     Route::post('bulk-deactivate')->name('bulk-deactivate')->uses('GuardiansController@bulkDeactivate');
                 });
             });
         });
});
