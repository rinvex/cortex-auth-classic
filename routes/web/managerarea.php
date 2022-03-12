<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Managerarea\RolesController;
use Cortex\Auth\Http\Controllers\Managerarea\MembersController;
use Cortex\Auth\Http\Controllers\Managerarea\ManagersController;
use Cortex\Auth\Http\Controllers\Managerarea\RedirectionController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountMediaController;
use Cortex\Auth\Http\Controllers\Managerarea\MembersMediaController;
use Cortex\Auth\Http\Controllers\Managerarea\ManagersMediaController;
use Cortex\Auth\Http\Controllers\Managerarea\PasswordResetController;
use Cortex\Auth\Http\Controllers\Managerarea\AuthenticationController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountPasswordController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountSessionsController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountSettingsController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountTwoFactorController;
use Cortex\Auth\Http\Controllers\Managerarea\ReauthenticationController;
use Cortex\Auth\Http\Controllers\Managerarea\AccountAttributesController;
use Cortex\Auth\Http\Controllers\Managerarea\EmailVerificationController;
use Cortex\Auth\Http\Controllers\Managerarea\PhoneVerificationController;

Route::domain('{managerarea}')->group(function () {
    Route::name('managerarea.')
         ->middleware(['web', 'nohttpcache'])
         ->prefix(route_prefix('managerarea'))->group(function () {

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

             Route::middleware(['can:access-managerarea'])->group(function () {

                 // Account Settings Route Alias
                 Route::get('account')->name('cortex.auth.account')->uses([AccountSettingsController::class, 'index']);

                 // User Account Routes
                 Route::name('cortex.auth.account.')->prefix('account')->group(function () {
                     // Account Settings Routes
                     Route::get('settings')->name('settings')->uses([AccountSettingsController::class, 'edit']);
                     Route::post('settings')->name('settings.update')->uses([AccountSettingsController::class, 'update']);

                     // Account Media Routes
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses([AccountMediaController::class, 'destroy']);

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

                 // Roles Routes
                 Route::name('cortex.auth.roles.')->prefix('roles')->group(function () {
                     Route::get('/')->name('index')->uses([RolesController::class, 'index']);
                     Route::post('import')->name('import')->uses([RolesController::class, 'import']);
                     Route::get('create')->name('create')->uses([RolesController::class, 'create']);
                     Route::post('create')->name('store')->uses([RolesController::class, 'store']);
                     Route::get('{role}')->name('show')->uses([RolesController::class, 'show']);
                     Route::get('{role}/edit')->name('edit')->uses([RolesController::class, 'edit']);
                     Route::put('{role}/edit')->name('update')->uses([RolesController::class, 'update']);
                     Route::match(['get', 'post'], '{role}/logs')->name('logs')->uses([RolesController::class, 'logs']);
                     Route::delete('{role}')->name('destroy')->uses([RolesController::class, 'destroy']);
                 });

                 // Members Routes
                 Route::name('cortex.auth.members.')->prefix('members')->group(function () {
                     Route::get('/')->name('index')->uses([MembersController::class, 'index']);
                     Route::post('import')->name('import')->uses([MembersController::class, 'import']);
                     Route::get('create')->name('create')->uses([MembersController::class, 'create']);
                     Route::post('create')->name('store')->uses([MembersController::class, 'store']);
                     Route::get('{member}')->name('show')->uses([MembersController::class, 'show']);
                     Route::get('{member}/edit')->name('edit')->uses([MembersController::class, 'edit']);
                     Route::put('{member}/edit')->name('update')->uses([MembersController::class, 'update']);
                     Route::match(['get', 'post'], '{member}/logs')->name('logs')->uses([MembersController::class, 'logs']);
                     Route::match(['get', 'post'], '{member}/activities')->name('activities')->uses([MembersController::class, 'activities']);
                     Route::get('{member}/attributes')->name('attributes')->uses([MembersController::class, 'attributes']);
                     Route::put('{member}/attributes')->name('attributes.update')->uses([MembersController::class, 'updateAttributes']);
                     Route::delete('{member}')->name('destroy')->uses([MembersController::class, 'destroy']);
                     Route::delete('{member}/media/{media}')->name('media.destroy')->uses([MembersMediaController::class, 'destroy']);
                 });

                 // Managers Routes
                 Route::name('cortex.auth.managers.')->prefix('managers')->group(function () {
                     Route::get('/')->name('index')->uses([ManagersController::class, 'index']);
                     Route::post('import')->name('import')->uses([ManagersController::class, 'import']);
                     Route::get('create')->name('create')->uses([ManagersController::class, 'create']);
                     Route::post('create')->name('store')->uses([ManagersController::class, 'store']);
                     Route::get('{manager}')->name('show')->uses([ManagersController::class, 'show']);
                     Route::get('{manager}/edit')->name('edit')->uses([ManagersController::class, 'edit']);
                     Route::put('{manager}/edit')->name('update')->uses([ManagersController::class, 'update']);
                     Route::match(['get', 'post'], '{manager}/logs')->name('logs')->uses([ManagersController::class, 'logs']);
                     Route::match(['get', 'post'], '{manager}/activities')->name('activities')->uses([ManagersController::class, 'activities']);
                     Route::get('{manager}/attributes')->name('attributes')->uses([ManagersController::class, 'attributes']);
                     Route::put('{manager}/attributes')->name('attributes.update')->uses([ManagersController::class, 'updateAttributes']);
                     Route::delete('{manager}')->name('destroy')->uses([ManagersController::class, 'destroy']);
                     Route::delete('{manager}/media/{media}')->name('media.destroy')->uses([ManagersMediaController::class, 'destroy']);
                 });
             });
         });
});
