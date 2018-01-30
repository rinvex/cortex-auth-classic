<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Illuminate\Http\Request;
use Rinvex\Fort\Models\Role;
use Rinvex\Fort\Models\User;
use Illuminate\Routing\Router;
use Rinvex\Fort\Models\Ability;
use Rinvex\Fort\Models\Session;
use Illuminate\Support\ServiceProvider;
use Cortex\Fort\Handlers\GenericHandler;
use Cortex\Fort\Http\Middleware\Abilities;
use Cortex\Fort\Http\Middleware\NoHttpCache;
use Cortex\Fort\Http\Middleware\Authenticate;
use Cortex\Fort\Console\Commands\SeedCommand;
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
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'cortex.fort');

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        // Attach request macro
        $this->attachRequestMacro();

        // Bind route models and constrains
        $router->pattern('ability', '[0-9]+');
        $router->pattern('role', '[a-z0-9-]+');
        $router->pattern('user', '[a-zA-Z0-9_-]+');
        $router->pattern('session', '[a-zA-Z0-9]+');
        $router->model('ability', Ability::class);
        $router->model('session', Session::class);
        $router->model('role', Role::class);
        $router->model('user', User::class);

        // Map relations
        Relation::morphMap([
            'role' => config('rinvex.fort.models.role'),
            'ability' => config('rinvex.fort.models.ability'),
            'user' => config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model'),
        ]);

        // Load resources
        require __DIR__.'/../../routes/breadcrumbs.php';
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/fort');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/fort');
        $this->app->afterResolving('blade.compiler', function () {
            require __DIR__.'/../../routes/menus.php';
        });

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();

        // Register event handlers
        $this->app['events']->subscribe(GenericHandler::class);

        // Register attributes entities
        app('rinvex.attributes.entities')->push('user');

        // Override middlware
        $this->overrideMiddleware($router);

        // Register menus
        $this->registerMenus();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('cortex.fort.php')], 'cortex-fort-config');
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
            $twofactor = $this->session()->get('rinvex.fort.twofactor');

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
        $router->pushMiddlewareToGroup('web', Abilities::class);
        $router->pushMiddlewareToGroup('web', UpdateLastActivity::class);

        // Override route middleware on the fly
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('nohttpcache', NoHttpCache::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
    }
}
