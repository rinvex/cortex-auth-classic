<?php

declare(strict_types=1);

namespace Cortex\Auth\Providers;

use Bouncer;
use Cortex\Auth\Models\Role;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Illuminate\Routing\Router;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Session;
use Cortex\Auth\Models\Guardian;
use Cortex\Auth\Models\Socialite;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Auth\Http\Middleware\Authorize;
use Illuminate\Contracts\Events\Dispatcher;
use Cortex\Auth\Http\Middleware\Reauthenticate;
use Cortex\Auth\Http\Middleware\UpdateTimezone;
use Illuminate\Auth\Middleware\RequirePassword;
use Cortex\Auth\Http\Middleware\UpdateLastActivity;
use Cortex\Auth\Http\Middleware\AuthenticateSession;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Cortex\Auth\Http\Middleware\RedirectIfAuthenticated;

class AuthServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        $this->app['config']->set('auth.model', config('cortex.auth.models.member'));

        // Bind eloquent models to IoC container
        $this->registerModels([
            'cortex.auth.session' => Session::class,
            'cortex.auth.socialite' => Socialite::class,
            'cortex.auth.admin' => Admin::class,
            'cortex.auth.member' => Member::class,
            'cortex.auth.manager' => Manager::class,
            'cortex.auth.guardian' => Guardian::class,
            'cortex.auth.role' => Role::class,
            'cortex.auth.ability' => Ability::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $dispatcher): void
    {
        // Map bouncer models
        Bouncer::ownedVia('created_by');
        Bouncer::useRoleModel(config('cortex.auth.models.role'));
        Bouncer::useAbilityModel(config('cortex.auth.models.ability'));

        // Map bouncer tables (users, roles, abilities tables are set through their models)
        Bouncer::tables([
            'permissions' => config('cortex.auth.tables.permissions'),
            'assigned_roles' => config('cortex.auth.tables.assigned_roles'),
        ]);

        // Bind route models and constrains
        $router->pattern('role', '[a-zA-Z0-9-_]+');
        $router->pattern('ability', '[a-zA-Z0-9-_]+');
        $router->pattern('session', '[a-zA-Z0-9-_]+');
        $router->pattern('admin', '[a-zA-Z0-9-_]+');
        $router->pattern('member', '[a-zA-Z0-9-_]+');
        $router->pattern('manager', '[a-zA-Z0-9-_]+');
        $router->model('role', config('cortex.auth.models.role'));
        $router->model('admin', config('cortex.auth.models.admin'));
        $router->model('member', config('cortex.auth.models.member'));
        $router->model('manager', config('cortex.auth.models.manager'));
        $router->model('guardian', config('cortex.auth.models.guardian'));
        $router->model('ability', config('cortex.auth.models.ability'));
        $router->model('session', config('cortex.auth.models.session'));

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
            // Attach request macro
            $this->attachRequestMacro();

            // Override middleware
            $this->overrideMiddleware($router);

            // Register menus
            $this->registerMenus();
        }
    }

    /**
     * Register attemptUser request macro.
     *
     * @return void
     */
    protected function attachRequestMacro(): void
    {
        Request::macro('attemptUser', function (string $guard = null) {
            $twofactor = $this->session()->get('cortex.auth.twofactor');

            return auth()->guard($guard)->getProvider()->retrieveById($twofactor['user_id'] ?? null);
        });
    }

    /**
     * Register menus.
     *
     * @return void
     */
    protected function registerMenus(): void
    {
        $this->app['rinvex.menus.presenters']->put('account.sidebar', \Cortex\Auth\Presenters\AccountSidebarMenuPresenter::class);
    }

    /**
     * Override middleware.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function overrideMiddleware(Router $router): void
    {
        // Append middleware to the 'web' middleware group
        $router->pushMiddlewareToGroup('web', AuthenticateSession::class);
        $router->pushMiddlewareToGroup('web', UpdateLastActivity::class);
        $router->pushMiddlewareToGroup('web', UpdateTimezone::class);

        // Override route middleware on the fly
        $router->aliasMiddleware('can', Authorize::class);
        $router->aliasMiddleware('reauthenticate', Reauthenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('verified', EnsureEmailIsVerified::class);
        $router->aliasMiddleware('password.confirm', RequirePassword::class);
    }
}
