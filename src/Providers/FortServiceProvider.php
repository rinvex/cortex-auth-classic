<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Cortex\Fort\Models\Ability;
use Illuminate\Support\ServiceProvider;
use Cortex\Fort\Console\Commands\SeedCommand;
use Cortex\Fort\Console\Commands\MigrateCommand;

class FortServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.cortex.fort.migrate',
        SeedCommand::class => 'command.cortex.fort.seed',
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
    public function register()
    {
        // Bind eloquent models to IoC container
        $this->app->alias('rinvex.fort.ability', Ability::class);
        $this->app->alias('rinvex.fort.role', Role::class);
        $this->app->alias('rinvex.fort.user', User::class);

        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load resources
        require __DIR__.'/../../routes/breadcrumbs.php';
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.frontend.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.userarea.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.backend.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/fort');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/fort');
        $this->app->afterResolving('blade.compiler', function () {
            require __DIR__.'/../../routes/menus.php';
        });

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();

        // Register attributable entities
        app('rinvex.attributable.entities')->push(app('rinvex.fort.role')->class);
        app('rinvex.attributable.entities')->push(app('rinvex.fort.user')->class);
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/fort')], 'lang');
        $this->publishes([realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/fort')], 'views');
    }
}
