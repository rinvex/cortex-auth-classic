<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Tenantarea\RedirectionController;
use Cortex\Auth\Http\Controllers\Tenantarea\AccountMediaController;
use Cortex\Auth\Http\Controllers\Tenantarea\PasswordResetController;
use Cortex\Auth\Http\Controllers\Tenantarea\AuthenticationController;
use Cortex\Auth\Http\Controllers\Tenantarea\AccountPasswordController;
use Cortex\Auth\Http\Controllers\Tenantarea\AccountSessionsController;
use Cortex\Auth\Http\Controllers\Tenantarea\AccountSettingsController;
use Cortex\Auth\Http\Controllers\Tenantarea\AccountTwoFactorController;
use Cortex\Auth\Http\Controllers\Tenantarea\ReauthenticationController;
use Cortex\Auth\Http\Controllers\Tenantarea\EmailVerificationController;
use Cortex\Auth\Http\Controllers\Tenantarea\PhoneVerificationController;
use Cortex\Auth\Http\Controllers\Tenantarea\MemberRegistrationController;
use Cortex\Auth\Http\Controllers\Tenantarea\SocialAuthenticationController;

Route::domain('{tenantarea}')->group(function () {
    Route::name('tenantarea.')
        ->middleware(['web', 'nohttpcache'])
        ->prefix(route_prefix('tenantarea'))->group(function () {

            // Authenticate broadcasting to channels
            Route::match(['get', 'post'], 'broadcasting/auth')->name('broadcast')->uses([AuthenticationController::class, 'broadcast']);

            Route::name('cortex.auth.account.')->group(function () {
                $maxAttempts = config('cortex.auth.throttle.login.max_attempts');
                $decayMinutes = config('cortex.auth.throttle.login.decay_minutes');

                // Login Routes
                Route::redirect('auth', '/login')->name('auth');
                Route::redirect('auth/login', '/login')->name('auth.login');
                Route::redirect('auth/register', '/register')->name('auth.register');
                Route::get('login')->name('login')->uses([AuthenticationController::class, 'form']);
                Route::post('login')->name('login.process')->middleware("throttle:$maxAttempts,$decayMinutes")->uses([AuthenticationController::class, 'login']);
                Route::post('logout')->name('logout')->uses([AuthenticationController::class, 'logout']);

                // Social Authentication Routes
                Route::get('auth/{provider}')->name('auth.social')->uses([SocialAuthenticationController::class, 'redirectToProvider']);
                Route::get('auth/{provider}/callback')->name('auth.social.callback')->uses([SocialAuthenticationController::class, 'handleProviderCallback']);

                // Registration Routes
                Route::get('register')->name('register')->uses([MemberRegistrationController::class, 'form']);
                Route::post('register')->name('register.process')->uses([MemberRegistrationController::class, 'register']);

                // Reauthentication Routes
                Route::name('reauthentication.')->prefix('reauthentication')->group(function () {
                    Route::get('password')->name('password')->uses([ReauthenticationController::class, 'confirmPassword']);
                    Route::post('password')->name('password.process')->uses([ReauthenticationController::class, 'processPassword']);

                    Route::get('twofactor')->name('twofactor')->uses([ReauthenticationController::class, 'confirmTwofactor']);
                    Route::post('twofactor')->name('twofactor.process')->uses([ReauthenticationController::class, 'processTwofactor']);
                });

                // Password Reset Routes
                Route::get('passwordreset')->name('passwordreset')->uses([RedirectionController::class, 'passwordreset']);
                Route::name('passwordreset.')->prefix('passwordreset')->group(function () use ($maxAttempts, $decayMinutes) {
                    Route::get('request')->name('request')->uses([PasswordResetController::class, 'request']);
                    Route::post('send')->name('send')->middleware("throttle:$maxAttempts,$decayMinutes")->uses([PasswordResetController::class, 'send']);
                    Route::get('reset')->name('reset')->uses([PasswordResetController::class, 'reset']);
                    Route::post('process')->name('process')->uses([PasswordResetController::class, 'process']);
                });

                // Verification Routes
                Route::get('verification')->name('verification')->uses([RedirectionController::class, 'verification']);
                Route::name('verification.')->prefix('verification')->group(function () {
                    // Phone Verification Routes
                    Route::name('phone.')->prefix('phone')->group(function () {
                        Route::get('request')->name('request')->uses([PhoneVerificationController::class, 'request']);
                        Route::post('send')->name('send')->uses([PhoneVerificationController::class, 'send']);
                        Route::get('verify')->name('verify')->uses([PhoneVerificationController::class, 'verify']);
                        Route::post('process')->name('process')->uses([PhoneVerificationController::class, 'process']);
                    });

                    // Email Verification Routes
                    Route::name('email.')->prefix('email')->group(function () {
                        Route::get('request')->name('request')->uses([EmailVerificationController::class, 'request']);
                        Route::post('send')->name('send')->uses([EmailVerificationController::class, 'send']);
                        Route::get('verify')->name('verify')->uses([EmailVerificationController::class, 'verify']);
                    });
                });
            });

            // Account Settings Route Alias
            Route::get('account')->name('cortex.auth.account')->uses([AccountSettingsController::class, 'index']);

            // User Account Routes
            Route::name('cortex.auth.account.')->prefix('account')->group(function () {
                // Account Settings Routes
                Route::get('settings')->name('settings')->uses([AccountSettingsController::class, 'edit']);
                Route::post('settings')->name('settings.update')->uses([AccountSettingsController::class, 'update']);

                // Account Media Routes
                Route::delete('{member}/media/{media}')->name('media.destroy')->uses([AccountMediaController::class, 'destroy']);

                // Account Password Routes
                Route::get('password')->name('password')->uses([AccountPasswordController::class, 'edit']);
                Route::post('password')->name('password.update')->uses([AccountPasswordController::class, 'update']);

                // Account Sessions Routes
                Route::get('sessions')->name('sessions')->uses([AccountSessionsController::class, 'index']);
                Route::delete('sessions')->name('sessions.flush')->uses([AccountSessionsController::class, 'flush']);
                Route::delete('sessions/{session?}')->name('sessions.destroy')->uses([AccountSessionsController::class, 'destroy']);

                // Account TwoFactor Routes
                Route::get('twofactor')->name('twofactor')->uses([AccountTwoFactorController::class, 'index']);
                Route::name('twofactor.')->prefix('twofactor')->group(function () {

                    // Account TwoFactor TOTP Routes
                    Route::name('totp.')->prefix('totp')->group(function () {
                        Route::get('enable')->name('enable')->uses([AccountTwoFactorController::class, 'enableTotp']);
                        Route::post('update')->name('update')->uses([AccountTwoFactorController::class, 'updateTotp']);
                        Route::post('disable')->name('disable')->uses([AccountTwoFactorController::class, 'disableTotp']);
                        Route::post('backup')->name('backup')->uses([AccountTwoFactorController::class, 'backupTotp']);
                    });

                    // Account TwoFactor Phone Routes
                    Route::name('phone.')->prefix('phone')->group(function () {
                        Route::post('enable')->name('enable')->uses([AccountTwoFactorController::class, 'enablePhone']);
                        Route::post('disable')->name('disable')->uses([AccountTwoFactorController::class, 'disablePhone']);
                    });
                });
            });
        });
});
