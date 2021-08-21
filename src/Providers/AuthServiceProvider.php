<?php

declare(strict_types=1);

namespace Cortex\Auth\Providers;

use Cortex\Auth\Models\Role;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Session;
use Cortex\Auth\Models\Guardian;
use Cortex\Auth\Models\Socialite;
use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Illuminate\Database\Eloquent\Relations\Relation;

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
    public function boot(): void
    {
        // Map bouncer models
        BouncerFacade::ownedVia('created_by');
        BouncerFacade::useRoleModel(config('cortex.auth.models.role'));
        BouncerFacade::useAbilityModel(config('cortex.auth.models.ability'));

        // Map bouncer tables (users, roles, abilities tables are set through their models)
        BouncerFacade::tables([
            'permissions' => config('cortex.auth.tables.permissions'),
            'assigned_roles' => config('cortex.auth.tables.assigned_roles'),
        ]);

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
            $this->extendRequest();
            $this->registerMenus();
        }
    }

    /**
     * Register attemptUser request macro.
     *
     * @return void
     */
    protected function extendRequest(): void
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
}
