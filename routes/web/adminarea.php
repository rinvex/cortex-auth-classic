<?php

declare(strict_types=1);

use Cortex\Auth\Http\Controllers\Adminarea\RolesController;
use Cortex\Auth\Http\Controllers\Adminarea\AdminsController;
use Cortex\Auth\Http\Controllers\Adminarea\MembersController;
use Cortex\Auth\Http\Controllers\Adminarea\AbilitiesController;
use Cortex\Auth\Http\Controllers\Adminarea\GuardiansController;
use Cortex\Auth\Http\Controllers\Adminarea\AdminsMediaController;
use Cortex\Auth\Http\Controllers\Adminarea\RedirectionController;
use Cortex\Auth\Http\Controllers\Adminarea\AccountMediaController;
use Cortex\Auth\Http\Controllers\Adminarea\MembersMediaController;
use Cortex\Auth\Http\Controllers\Adminarea\PasswordResetController;
use Cortex\Auth\Http\Controllers\Adminarea\AuthenticationController;
use Cortex\Auth\Http\Controllers\Adminarea\AccountPasswordController;
use Cortex\Auth\Http\Controllers\Adminarea\AccountSessionsController;
use Cortex\Auth\Http\Controllers\Adminarea\AccountSettingsController;
use Cortex\Auth\Http\Controllers\Adminarea\AccountTwoFactorController;
use Cortex\Auth\Http\Controllers\Adminarea\ReauthenticationController;
use Cortex\Auth\Http\Controllers\Adminarea\EmailVerificationController;
use Cortex\Auth\Http\Controllers\Adminarea\PhoneVerificationController;

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache'])
         ->prefix(route_prefix('adminarea'))->group(function () {
             // Authenticate broadcasting to channels
             Route::match(['get', 'post'], 'broadcasting/auth')->name('broadcast')->uses([AuthenticationController::class, 'broadcast']);

             Route::name('cortex.auth.account.')->group(function () {
                 $maxAttempts = config('cortex.auth.throttle.login.max_attempts');
                 $decayMinutes = config('cortex.auth.throttle.login.decay_minutes');

                 // Login Routes
                 Route::redirect('auth', '/login')->name('auth');
                 Route::redirect('auth/login', 'l/ogin')->name('auth.login');
                 Route::redirect('auth/register', '/register')->name('auth.register');
                 Route::get('login')->name('login')->uses([AuthenticationController::class, 'form']);
                 Route::post('login')->name('login.process')->uses([AuthenticationController::class, 'login']);
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

             Route::middleware(['can:access-adminarea'])->group(function () {
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

                 // Abilities Routes
                 Route::name('cortex.auth.abilities.')->prefix('abilities')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([AbilitiesController::class, 'index']);
                     Route::post('import')->name('import')->uses([AbilitiesController::class, 'import']);
                     Route::get('create')->name('create')->uses([AbilitiesController::class, 'create']);
                     Route::post('create')->name('store')->uses([AbilitiesController::class, 'store']);
                     Route::get('{ability}')->name('show')->uses([AbilitiesController::class, 'show']);
                     Route::get('{ability}/edit')->name('edit')->uses([AbilitiesController::class, 'edit']);
                     Route::put('{ability}/edit')->name('update')->uses([AbilitiesController::class, 'update']);
                     Route::match(['get', 'post'], '{ability}/logs')->name('logs')->uses([AbilitiesController::class, 'logs']);
                     Route::delete('{ability}')->name('destroy')->uses([AbilitiesController::class, 'destroy']);
                 });

                 // Roles Routes
                 Route::name('cortex.auth.roles.')->prefix('roles')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([RolesController::class, 'index']);
                     Route::post('import')->name('import')->uses([RolesController::class, 'import']);
                     Route::get('create')->name('create')->uses([RolesController::class, 'create']);
                     Route::post('create')->name('store')->uses([RolesController::class, 'store']);
                     Route::get('{role}')->name('show')->uses([RolesController::class, 'show']);
                     Route::get('{role}/edit')->name('edit')->uses([RolesController::class, 'edit']);
                     Route::put('{role}/edit')->name('update')->uses([RolesController::class, 'update']);
                     Route::match(['get', 'post'], '{role}/logs')->name('logs')->uses([RolesController::class, 'logs']);
                     Route::delete('{role}')->name('destroy')->uses([RolesController::class, 'destroy']);
                 });

                 // Admins Routes
                 Route::name('cortex.auth.admins.')->prefix('admins')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([AdminsController::class, 'index']);
                     Route::post('import')->name('import')->uses([AdminsController::class, 'import']);
                     Route::get('create')->name('create')->uses([AdminsController::class, 'create']);
                     Route::post('create')->name('store')->uses([AdminsController::class, 'store']);
                     Route::get('{admin}')->name('show')->uses([AdminsController::class, 'show']);
                     Route::get('{admin}/edit')->name('edit')->uses([AdminsController::class, 'edit']);
                     Route::put('{admin}/edit')->name('update')->uses([AdminsController::class, 'update']);
                     Route::match(['get', 'post'], '{admin}/logs')->name('logs')->uses([AdminsController::class, 'logs']);
                     Route::match(['get', 'post'], '{admin}/activities')->name('activities')->uses([AdminsController::class, 'activities']);
                     Route::delete('{admin}')->name('destroy')->uses([AdminsController::class, 'destroy']);
                     Route::delete('{admin}/media/{media}')->name('media.destroy')->uses([AdminsMediaController::class, 'destroy']);
                 });

                 // Members Routes
                 Route::name('cortex.auth.members.')->prefix('members')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([MembersController::class, 'index']);
                     Route::post('ajax')->name('ajax')->uses([MembersController::class, 'ajax']); // @TODO: to be refactored!
                     Route::post('import')->name('import')->uses([MembersController::class, 'import']);
                     Route::get('create')->name('create')->uses([MembersController::class, 'create']);
                     Route::post('create')->name('store')->uses([MembersController::class, 'store']);
                     Route::get('{member}')->name('show')->uses([MembersController::class, 'show']);
                     Route::get('{member}/edit')->name('edit')->uses([MembersController::class, 'edit']);
                     Route::put('{member}/edit')->name('update')->uses([MembersController::class, 'update']);
                     Route::match(['get', 'post'], '{member}/logs')->name('logs')->uses([MembersController::class, 'logs']);
                     Route::match(['get', 'post'], '{member}/activities')->name('activities')->uses([MembersController::class, 'activities']);
                     Route::delete('{member}')->name('destroy')->uses([MembersController::class, 'destroy']);
                     Route::delete('{member}/media/{media}')->name('media.destroy')->uses([MembersMediaController::class, 'destroy']);
                 });

                 // Guardians Routes
                 Route::name('cortex.auth.guardians.')->prefix('guardians')->group(function () {
                     Route::match(['get', 'post'], '/')->name('index')->uses([GuardiansController::class, 'index']);
                     Route::post('import')->name('import')->uses([GuardiansController::class, 'import']);
                     Route::get('create')->name('create')->uses([GuardiansController::class, 'create']);
                     Route::post('create')->name('store')->uses([GuardiansController::class, 'store']);
                     Route::get('{guardian}')->name('show')->uses([GuardiansController::class, 'show']);
                     Route::get('{guardian}/edit')->name('edit')->uses([GuardiansController::class, 'edit']);
                     Route::put('{guardian}/edit')->name('update')->uses([GuardiansController::class, 'update']);
                     Route::match(['get', 'post'], '{guardian}/logs')->name('logs')->uses([GuardiansController::class, 'logs']);
                     Route::delete('{guardian}')->name('destroy')->uses([GuardiansController::class, 'destroy']);
                 });
             });
         });
});
