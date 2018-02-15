<?php

declare(strict_types=1);

if (! function_exists('authentication_routes')) {
    /**
     * Register authenication routes.
     *
     * @return void
     */
    function authentication_routes()
    {
        // Login Routes
        Route::get('login')->name('login')->uses('AuthenticationController@form');
        Route::post('login')->name('login.process')->uses('AuthenticationController@login');
        Route::post('logout')->name('logout')->uses('AuthenticationController@logout');

        // Registration Routes
        Route::get('register')->name('register')->uses('RegistrationController@form');
        Route::post('register')->name('register.process')->uses('RegistrationController@register');

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

        // Account Settings Route Placeholder
        Route::redirect('account', '/account/settings')->name('account')->uses('AccountSettingsController@index');

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
    }
}

Route::domain(domain())->group(function () {
    Route::name('frontarea.')
        ->middleware(['web', 'nohttpcache'])
        ->namespace('Cortex\Fort\Http\Controllers\Frontarea')
        ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.frontarea') : config('cortex.foundation.route.prefix.frontarea'))->group(function () {

            // Authenication routes
            authentication_routes();

        });


    Route::name('adminarea.')
         ->namespace('Cortex\Fort\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

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

         // Users Routes
         Route::name('users.')->prefix('users')->group(function () {
             Route::get('/')->name('index')->uses('UsersController@index');
             Route::get('create')->name('create')->uses('UsersController@create');
             Route::post('create')->name('store')->uses('UsersController@store');
             Route::get('{user}')->name('edit')->uses('UsersController@edit');
             Route::put('{user}')->name('update')->uses('UsersController@update');
             Route::get('{user}/logs')->name('logs')->uses('UsersController@logs');
             Route::get('{user}/activities')->name('activities')->uses('UsersController@activities');
             Route::get('{user}/attributes')->name('attributes')->uses('UsersController@attributes');
             Route::put('{user}/attributes')->name('attributes.update')->uses('UsersController@updateAttributes');
             Route::delete('{user}')->name('destroy')->uses('UsersController@destroy');
             Route::delete('{user}/media/{media}')->name('media.destroy')->uses('UsersMediaController@destroy');
         });
     });
});


Route::domain('{subdomain}.'.domain())->group(function () {

    Route::name('managerarea.')
         ->namespace('Cortex\Fort\Http\Controllers\Managerarea')
         ->middleware(['web', 'nohttpcache', 'can:access-managerarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.tenants.route.prefix.managerarea') : config('cortex.tenants.route.prefix.managerarea'))->group(function () {

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

         // Users Routes
         Route::name('users.')->prefix('users')->group(function () {
             Route::get('/')->name('index')->uses('UsersController@index');
             Route::get('create')->name('create')->uses('UsersController@create');
             Route::post('create')->name('store')->uses('UsersController@store');
             Route::get('{user}')->name('edit')->uses('UsersController@edit');
             Route::put('{user}')->name('update')->uses('UsersController@update');
             Route::get('{user}/logs')->name('logs')->uses('UsersController@logs');
             Route::get('{user}/activities')->name('activities')->uses('UsersController@activities');
             Route::get('{user}/attributes')->name('attributes')->uses('UsersController@attributes');
             Route::put('{user}/attributes')->name('attributes.update')->uses('UsersController@updateAttributes');
             Route::delete('{user}')->name('destroy')->uses('UsersController@destroy');
             Route::delete('{user}/media/{media}')->name('media.destroy')->uses('UsersMediaController@destroy');
         });
     });


    Route::name('tenantarea.')
        ->middleware(['web', 'nohttpcache'])
        ->namespace('Cortex\Fort\Http\Controllers\Tenantarea')
        ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.tenantarea') : config('cortex.foundation.route.prefix.tenantarea'))->group(function () {

        // Authenication routes
        authentication_routes();

        // Social Authentication Routes
        Route::redirect('auth', 'login');
        Route::get('auth/{provider}')->name('auth.social')->uses('SocialAuthenticationController@redirectToProvider');
        Route::get('auth/{provider}/callback')->name('auth.social.callback')->uses('SocialAuthenticationController@handleProviderCallback');

    });

});
