<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Fort Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Fort Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class FortServiceProvider extends ServiceProvider
{
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
        //
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
            // Load the application routes
            $router->namespace('Cortex\Fort\Http\Controllers')
                   ->prefix($this->app['config']['rinvex.cortex.route.locale_prefix'] ? '{locale}' : '')
                   ->middleware('web')
                   ->group(__DIR__.'/../../routes/web.php');

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
