<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Bouncer;
use Cortex\Fort\Models\Role;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Admin;
use Cortex\Fort\Models\Member;
use Illuminate\Routing\Router;
use Cortex\Fort\Models\Manager;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\Models\Session;
use Cortex\Fort\Models\Sentinel;
use Cortex\Fort\Models\Socialite;
use Illuminate\Support\ServiceProvider;
use Cortex\Fort\Handlers\GenericHandler;
use Cortex\Fort\Console\Commands\SeedCommand;
use Cortex\Fort\Http\Middleware\Reauthenticate;
use Cortex\Fort\Console\Commands\InstallCommand;
use Cortex\Fort\Console\Commands\MigrateCommand;
use Cortex\Fort\Console\Commands\PublishCommand;
use Cortex\Fort\Console\Commands\RollbackCommand;
use Cortex\Fort\Http\Middleware\UpdateLastActivity;
use Illuminate\Database\Eloquent\Relations\Relation;
use Cortex\Fort\Http\Middleware\RedirectIfAuthenticated;

class FortServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        SeedCommand::class => 'command.cortex.fort.seed',
        InstallCommand::class => 'command.cortex.fort.install',
        MigrateCommand::class => 'command.cortex.fort.migrate',
        PublishCommand::class => 'command.cortex.fort.publish',
        RollbackCommand::class => 'command.cortex.fort.rollback',
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
        // Merge config
        $this->app['config']->set('auth.model', config('cortex.fort.models.member'));
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'cortex.fort');

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();

        // Bind eloquent models to IoC container
        $this->app->singleton('cortex.fort.session', $sessionModel = $this->app['config']['cortex.fort.models.session']);
        $sessionModel === Session::class || $this->app->alias('cortex.fort.session', Session::class);

        $this->app->singleton('cortex.fort.socialite', $socialiteModel = $this->app['config']['cortex.fort.models.socialite']);
        $socialiteModel === Socialite::class || $this->app->alias('cortex.fort.socialite', Socialite::class);

        $this->app->singleton('cortex.fort.admin', $adminModel = $this->app['config']['cortex.fort.models.admin']);
        $adminModel === Admin::class || $this->app->alias('cortex.fort.admin', Admin::class);

        $this->app->singleton('cortex.fort.member', $memberModel = $this->app['config']['cortex.fort.models.member']);
        $memberModel === Member::class || $this->app->alias('cortex.fort.member', Member::class);

        $this->app->singleton('cortex.fort.manager', $managerModel = $this->app['config']['cortex.fort.models.manager']);
        $managerModel === Manager::class || $this->app->alias('cortex.fort.manager', Manager::class);

        $this->app->singleton('cortex.fort.sentinel', $sentinelModel = $this->app['config']['cortex.fort.models.sentinel']);
        $sentinelModel === Sentinel::class || $this->app->alias('cortex.fort.sentinel', Sentinel::class);

        $this->app->singleton('cortex.fort.role', $roleModel = $this->app['config']['cortex.fort.models.role']);
        $roleModel === Role::class || $this->app->alias('cortex.fort.role', Role::class);

        $this->app->singleton('cortex.fort.ability', $abilityModel = $this->app['config']['cortex.fort.models.ability']);
        $abilityModel === Ability::class || $this->app->alias('cortex.fort.ability', Ability::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        // Attach request macro
        $this->attachRequestMacro();

        // Map bouncer models
        Bouncer::useRoleModel(config('cortex.fort.models.role'));
        Bouncer::useUserModel(config('cortex.fort.models.member'));
        Bouncer::useAbilityModel(config('cortex.fort.models.ability'));

        // Map bouncer tables (users, roles, abilities tables are set through their models)
        Bouncer::tables([
            'permissions' => config('cortex.fort.tables.permissions'),
            'assigned_roles' => config('cortex.fort.tables.assigned_roles'),
        ]);

        // Bind route models and constrains
        $router->pattern('role', '[0-9]+');
        $router->pattern('ability', '[0-9]+');
        $router->pattern('user', '[a-zA-Z0-9_-]+');
        $router->pattern('session', '[a-zA-Z0-9]+');
        $router->model('role', config('cortex.fort.models.role'));
        $router->model('admin', config('cortex.fort.models.admin'));
        $router->model('member', config('cortex.fort.models.member'));
        $router->model('manager', config('cortex.fort.models.manager'));
        $router->model('sentinel', config('cortex.fort.models.sentinel'));
        $router->model('ability', config('cortex.fort.models.ability'));
        $router->model('session', config('cortex.fort.models.session'));

        // Map relations
        Relation::morphMap([
            'role' => config('cortex.fort.models.role'),
            'admin' => config('cortex.fort.models.admin'),
            'member' => config('cortex.fort.models.member'),
            'manager' => config('cortex.fort.models.manager'),
            'sentinel' => config('cortex.fort.models.sentinel'),
            'ability' => config('cortex.fort.models.ability'),
        ]);

        // Load resources
        require __DIR__.'/../../routes/breadcrumbs.php';
        $this->loadRoutesFrom(__DIR__.'/../../routes/http.adminarea.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/fort');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/fort');
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->app->afterResolving('blade.compiler', function () {
            require __DIR__.'/../../routes/menus.php';
        });

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();

        // Register event handlers
        $this->app['events']->subscribe(GenericHandler::class);

        // Register attributes entities
        ! app()->bound('rinvex.attributes.entities') || app('rinvex.attributes.entities')->push('user');

        // Override middlware
        $this->overrideMiddleware($router);

        // Register menus
        $this->registerMenus();

        // Share current user instance with all views
        $this->app['view']->composer('*', function ($view) {
            $view->with('currentUser', auth()->guard(request('guard'))->user());
        });
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('cortex.fort.php')], 'cortex-fort-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'cortex-fort-migrations');
        $this->publishes([realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/fort')], 'cortex-fort-lang');
        $this->publishes([realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/fort')], 'cortex-fort-views');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function attachRequestMacro(): void
    {
        Request::macro('attemptUser', function (string $guard = null) {
            $twofactor = $this->session()->get('cortex.fort.twofactor');

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
        $this->app['rinvex.menus.presenters']->put('account.sidebar', \Cortex\Fort\Presenters\AccountSidebarMenuPresenter::class);
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
        $router->pushMiddlewareToGroup('web', UpdateLastActivity::class);

        // Override route middleware on the fly
        $router->aliasMiddleware('reauthenticate', Reauthenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
    }
}
