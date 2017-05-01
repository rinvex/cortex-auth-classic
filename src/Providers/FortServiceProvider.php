<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Cortex\Fort\Console\Commands\SeedCommand;

class FortServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'SeedCommand' => 'command.rinvex.coworkit.seed',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Load routes
        $this->loadRoutes($router);

        if ($this->app->runningInConsole()) {
            // Publish Resources
            $this->publishResources();
        }

        // Register a view file namespace
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/fort');

        // Load language phrases
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/fort');
    }

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
        if ($this->app->runningInConsole()) {
            // Register artisan commands
            foreach (array_keys($this->commands) as $command) {
                call_user_func_array([$this, "register{$command}Command"], []);
            }

            $this->commands(array_values($this->commands));
        }
    }

    /**
     * Register make auth command.
     *
     * @return void
     */
    protected function registerSeedCommandCommand()
    {
        $this->app->singleton('command.rinvex.coworkit.seed', function ($app) {
            return new SeedCommand();
        });
    }

    /**
     * Load the module routes.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function loadRoutes(Router $router)
    {
        // Load routes
        if ($this->app->routesAreCached()) {
            $this->app->booted(function () {
                require $this->app->getCachedRoutesPath();
            });
        } else {
            // Load Routes
            require __DIR__.'/../../routes/frontend.php';
            require __DIR__.'/../../routes/backend.php';

            $this->app->booted(function () use ($router) {
                $router->getRoutes()->refreshNameLookups();
            });
        }
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        // Publish views
        $this->publishes([
            realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/fort'),
        ], 'views');

        // Publish language phrases
        $this->publishes([
            realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/fort'),
        ], 'lang');
    }
}
