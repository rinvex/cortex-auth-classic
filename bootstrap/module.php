<?php

declare(strict_types=1);

use Cortex\Auth\Http\Middleware\Authorize;
use Cortex\Auth\Http\Middleware\Reauthenticate;
use Cortex\Auth\Http\Middleware\UpdateTimezone;
use Illuminate\Auth\Middleware\RequirePassword;
use Cortex\Auth\Http\Middleware\UpdateLastActivity;
use Cortex\Auth\Http\Middleware\AuthenticateSession;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Cortex\Auth\Http\Middleware\RedirectIfAuthenticated;

return function () {
    // Bind route models and constrains
    Route::pattern('role', '[a-zA-Z0-9-_]+');
    Route::pattern('ability', '[a-zA-Z0-9-_]+');
    Route::pattern('session', '[a-zA-Z0-9-_]+');
    Route::pattern('admin', '[a-zA-Z0-9-_]+');
    Route::pattern('member', '[a-zA-Z0-9-_]+');
    Route::pattern('manager', '[a-zA-Z0-9-_]+');
    Route::model('role', config('cortex.auth.models.role'));
    Route::model('admin', config('cortex.auth.models.admin'));
    Route::model('member', config('cortex.auth.models.member'));
    Route::model('manager', config('cortex.auth.models.manager'));
    Route::model('guardian', config('cortex.auth.models.guardian'));
    Route::model('ability', config('cortex.auth.models.ability'));
    Route::model('session', config('cortex.auth.models.session'));

    if (! $this->app->runningInConsole()) {
        // Append middleware to the 'web' middleware group
        Route::pushMiddlewareToGroup('web', AuthenticateSession::class);
        Route::pushMiddlewareToGroup('web', UpdateLastActivity::class);
        Route::pushMiddlewareToGroup('web', UpdateTimezone::class);

        // Override route middleware on the fly
        Route::aliasMiddleware('can', Authorize::class);
        Route::aliasMiddleware('reauthenticate', Reauthenticate::class);
        Route::aliasMiddleware('guest', RedirectIfAuthenticated::class);
        Route::aliasMiddleware('verified', EnsureEmailIsVerified::class);
        Route::aliasMiddleware('password.confirm', RequirePassword::class);
    }
};
