<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
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
        'SeedCommand' => 'command.rinvex.cortex.seed',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load resources
        $this->loadRoutesFrom(__DIR__.'/../../routes/frontend.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/userarea.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/backend.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/fort');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/fort');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();

        // Register sidebar menus
        $this->app->singleton('menus.sidebar.access', function ($app) {
            return collect();
        });

        // Register menu items
        $this->app['view']->composer('cortex/foundation::backend.partials.sidebar', function ($view) {
            app('menus.sidebar')->put('access', app('menus.sidebar.access'));
            app('menus.sidebar.access')->put('header', '<li class="header">'.trans('cortex/fort::navigation.headers.access').'</li>');
            app('menus.sidebar.access')->put('abilities', '<li '.(mb_strpos(request()->route()->getName(), 'backend.abilities.') === 0 ? 'class="active"' : '').'><a href="'.route('backend.abilities.index').'"><i class="fa fa-sliders"></i> <span>'.trans('cortex/fort::navigation.menus.abilities').'</span></a></li>');
            app('menus.sidebar.access')->put('roles', '<li '.(mb_strpos(request()->route()->getName(), 'backend.roles.') === 0 ? 'class="active"' : '').'><a href="'.route('backend.roles.index').'"><i class="fa fa-users"></i> <span>'.trans('cortex/fort::navigation.menus.roles').'</span></a></li>');
            app('menus.sidebar.access')->put('users', '<li '.(mb_strpos(request()->route()->getName(), 'backend.users.') === 0 ? 'class="active"' : '').'><a href="'.route('backend.users.index').'"><i class="fa fa-user"></i> <span>'.trans('cortex/fort::navigation.menus.users').'</span></a></li>');
        });

        // Register attributable entities
        app('rinvex.attributable.entities')->push(Role::class);
        app('rinvex.attributable.entities')->push(User::class);

        // Register menu items
        $this->app['view']->composer('*.partials.header', function ($view) {
            app('menus.topbar')->put('user', view('cortex/fort::frontend.partials.topbar-user-menu')->render());
        });
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
        $this->app->singleton('command.rinvex.cortex.seed', function ($app) {
            return new SeedCommand();
        });
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
