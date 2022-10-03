<?php

declare(strict_types=1);

use Silber\Bouncer\BouncerFacade;
use Cortex\Foundation\Http\Kernel;
use Cortex\Auth\Http\Middleware\Authorize;
use Illuminate\Auth\Middleware\Authenticate;
use Cortex\Auth\Http\Middleware\ScopeBouncer;
use Cortex\Auth\Http\Middleware\Reauthenticate;
use Cortex\Auth\Http\Middleware\UpdateTimezone;
use Illuminate\Auth\Middleware\RequirePassword;
use Cortex\Auth\Http\Middleware\SetAuthDefaults;
use Cortex\Auth\Http\Middleware\UpdateLastActivity;
use Cortex\Auth\Http\Middleware\AuthenticateSession;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Cortex\Auth\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

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

    // Map bouncer models
    BouncerFacade::ownedVia('created_by');

    // Map relations
    Relation::morphMap([
        'role' => config('cortex.auth.models.role'),
        'admin' => config('cortex.auth.models.admin'),
        'member' => config('cortex.auth.models.member'),
        'manager' => config('cortex.auth.models.manager'),
        'guardian' => config('cortex.auth.models.guardian'),
        'ability' => config('cortex.auth.models.ability'),
    ]);

    if (! $this->app->runningInConsole()) {
        // Append middleware to the 'web' middleware group
        Route::pushMiddlewareToGroup('web', AuthenticateSession::class);
        Route::pushMiddlewareToGroup('web', UpdateLastActivity::class);
        Route::pushMiddlewareToGroup('web', UpdateTimezone::class);
        Route::pushMiddlewareToGroup('web', ScopeBouncer::class);

        // Add route middleware aliases on the fly
        Route::aliasMiddleware('can', Authorize::class);
        Route::aliasMiddleware('reauthenticate', Reauthenticate::class);
        Route::aliasMiddleware('guest', RedirectIfAuthenticated::class);
        Route::aliasMiddleware('verified', EnsureEmailIsVerified::class);
        Route::aliasMiddleware('password.confirm', RequirePassword::class);
    }

    // Register application's route middleware.
    Route::aliasMiddleware('auth', Authenticate::class);
    Route::aliasMiddleware('auth.basic', AuthenticateWithBasicAuth::class);

    // Push middleware to route group. These middleware are executed in every request in the GIVEN ORDER.
    Route::pushMiddlewareToGroup('web', SetAuthDefaults::class);

    // Update the priority-sorted list of middleware. This forces non-global middleware to always be in the GIVEN ORDER.
    $this->app[Kernel::class]->prependToMiddlewarePriority(SetAuthDefaults::class);
};
