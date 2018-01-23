<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Rinvex\Fort\Models\Role;
use Rinvex\Fort\Models\User;
use Illuminate\Routing\Router;
use Rinvex\Menus\Facades\Menu;
use Rinvex\Fort\Models\Ability;
use Illuminate\Support\ServiceProvider;
use Rinvex\Menus\Factories\MenuFactory;
use Cortex\Fort\Console\Commands\SeedCommand;
use Cortex\Fort\Console\Commands\InstallCommand;
use Cortex\Fort\Console\Commands\MigrateCommand;
use Cortex\Fort\Console\Commands\PublishCommand;
use Cortex\Fort\Console\Commands\RollbackCommand;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        $this->app->singleton('cortex.fort.user.tabs', function ($app) {
            return collect();
        });
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
        // Bind route models and constrains
        $router->pattern('ability', '[0-9]+');
        $router->pattern('role', '[a-z0-9-]+');
        $router->pattern('user', '[a-zA-Z0-9_-]+');
        $router->model('role', Role::class);
        $router->model('user', User::class);
        $router->model('ability', Ability::class);

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

        // Register attributes entities
        app('rinvex.attributes.entities')->push('user');

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
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register menus.
     *
     * @return void
     */
    protected function registerMenus(): void
    {
        $this->app['rinvex.menus.presenters']->put('user.sidebar', \Cortex\Fort\Presenters\UserSidebarMenuPresenter::class);

        Menu::make('frontarea.user.sidebar', function (MenuFactory $menu) {
        });
    }
}
