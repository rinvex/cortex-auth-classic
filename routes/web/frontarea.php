<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Frontarea\RedirectionController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountMediaController;
use Cortex\Auth\Http\Controllers\Frontarea\PasswordResetController;
use Cortex\Auth\Http\Controllers\Frontarea\AuthenticationController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountPasswordController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountSessionsController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountSettingsController;
use Cortex\Auth\Http\Controllers\Frontarea\ReauthenticationController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountTwoFactorController;
use Cortex\Auth\Http\Controllers\Frontarea\PhoneVerificationController;
use Cortex\Auth\Http\Controllers\Frontarea\AccountAttributesController;
use Cortex\Auth\Http\Controllers\Frontarea\EmailVerificationController;
use Cortex\Auth\Http\Controllers\Frontarea\MemberRegistrationController;
use Cortex\Auth\Http\Controllers\Frontarea\TenantRegistrationController;
use Cortex\Auth\Http\Controllers\Frontarea\SocialAuthenticationController;

Route::domain('{frontarea}')->group(function () {
    Route::name('frontarea.')
        ->middleware(['web', 'nohttpcache'])
        ->prefix(route_prefix('frontarea'))->group(function () {

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
                Route::get('register')->name('register')->uses([RedirectionController::class, 'registration']);
                Route::get('register/member')->name('register.member')->uses([MemberRegistrationController::class, 'form']);
                Route::post('register/member')->name('register.member.process')->uses([MemberRegistrationController::class, 'register']);

                // We can't register these two routes inside the managerarea, since the managerarea
                // is accessible only through the tenant domain/subdomain, and we did not create the tenant yet!
                Route::get('register/tenant')->name('register.tenant')->uses([TenantRegistrationController::class, 'form']);
                Route::post('register/tenant')->name('register.tenant.process')->uses([TenantRegistrationController::class, 'register']);

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

                // Account Attributes Routes
                Route::get('attributes')->name('attributes')->uses([AccountAttributesController::class, 'edit']);
                Route::post('attributes')->name('attributes.update')->uses([AccountAttributesController::class, 'update']);

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
