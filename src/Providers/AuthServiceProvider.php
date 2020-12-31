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
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Contracts\Events\Dispatcher;
use Cortex\Auth\Console\Commands\SeedCommand;
use Cortex\Auth\Console\Commands\UnloadCommand;
use Cortex\Auth\Http\Middleware\Reauthenticate;
use Cortex\Auth\Http\Middleware\UpdateTimezone;
use Illuminate\Auth\Middleware\RequirePassword;
use Cortex\Auth\Console\Commands\InstallCommand;
use Cortex\Auth\Console\Commands\MigrateCommand;
use Cortex\Auth\Console\Commands\PublishCommand;
use Cortex\Auth\Console\Commands\ActivateCommand;
use Cortex\Auth\Console\Commands\AutoloadCommand;
use Cortex\Auth\Console\Commands\RollbackCommand;
use Cortex\Auth\Console\Commands\DeactivateCommand;
use Cortex\Auth\Http\Middleware\UpdateLastActivity;
use Cortex\Auth\Http\Middleware\AuthenticateSession;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Cortex\Auth\Http\Middleware\RedirectIfAuthenticated;

class AuthServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        ActivateCommand::class => 'command.cortex.auth.activate',
        DeactivateCommand::class => 'command.cortex.auth.deactivate',
        AutoloadCommand::class => 'command.cortex.auth.autoload',
        UnloadCommand::class => 'command.cortex.auth.unload',

        SeedCommand::class => 'command.cortex.auth.seed',
        InstallCommand::class => 'command.cortex.auth.install',
        MigrateCommand::class => 'command.cortex.auth.migrate',
        PublishCommand::class => 'command.cortex.auth.publish',
        RollbackCommand::class => 'command.cortex.auth.rollback',
    ];

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

        // Register console commands
        $this->registerCommands($this->commands);

        // Bind eloquent models to IoC container
        $this->app->singleton('cortex.auth.session', $sessionModel = $this->app['config']['cortex.auth.models.session']);
        $sessionModel === Session::class || $this->app->alias('cortex.auth.session', Session::class);

        $this->app->singleton('cortex.auth.socialite', $socialiteModel = $this->app['config']['cortex.auth.models.socialite']);
        $socialiteModel === Socialite::class || $this->app->alias('cortex.auth.socialite', Socialite::class);

        $this->app->singleton('cortex.auth.admin', $adminModel = $this->app['config']['cortex.auth.models.admin']);
        $adminModel === Admin::class || $this->app->alias('cortex.auth.admin', Admin::class);

        $this->app->singleton('cortex.auth.member', $memberModel = $this->app['config']['cortex.auth.models.member']);
        $memberModel === Member::class || $this->app->alias('cortex.auth.member', Member::class);

        $this->app->singleton('cortex.auth.manager', $managerModel = $this->app['config']['cortex.auth.models.manager']);
        $managerModel === Manager::class || $this->app->alias('cortex.auth.manager', Manager::class);

        $this->app->singleton('cortex.auth.guardian', $guardianModel = $this->app['config']['cortex.auth.models.guardian']);
        $guardianModel === Guardian::class || $this->app->alias('cortex.auth.guardian', Guardian::class);

        $this->app->singleton('cortex.auth.role', $roleModel = $this->app['config']['cortex.auth.models.role']);
        $roleModel === Role::class || $this->app->alias('cortex.auth.role', Role::class);

        $this->app->singleton('cortex.auth.ability', $abilityModel = $this->app['config']['cortex.auth.models.ability']);
        $abilityModel === Ability::class || $this->app->alias('cortex.auth.ability', Ability::class);
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

            // Override middlware
            $this->overrideMiddleware($router);

            // Register menus
            $this->registerMenus();
        }
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function attachRequestMacro(): void
    {
        Request::macro('attemptUser', function (string $guard = null) {
            $twofactor = $this->session()->get('cortex.auth.twofactor');

            return auth()->guard($guard)->getProvider()->retrieveById($twofactor['user_id']);
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
        // Append middleware to the 'web' middlware group
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
