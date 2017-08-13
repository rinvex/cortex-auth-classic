<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {

    Route::name('userarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Fort\Http\Controllers\Userarea')
         ->prefix(config('rinvex.cortex.route.locale_prefix') ? '{locale}/userarea' : 'userarea')->group(function () {

        // Homepage Routes
        Route::get('/')->name('home')->uses('HomeController@index');

        // User Account Routes
        Route::name('account.')->prefix('account')->group(function () {
            // Account Page Routes
            Route::get('settings')->name('settings')->uses('AccountSettingsController@edit');
            Route::post('settings')->name('settings.update')->uses('AccountSettingsController@update');

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

});
