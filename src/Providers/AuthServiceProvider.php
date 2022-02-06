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
use Cortex\Auth\Guards\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Auth\Events\CurrentGuardLogout;

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
        if (! $this->app->runningInConsole()) {
            $this->extendAuthentication();
            $this->extendRequest();
            $this->registerMenus();
        }
    }

    /**
     * Register logoutCurrentGuard auth guard macro.
     *
     * @return void
     */
    protected function extendAuthentication(): void
    {
        /**
         * Create a session based authentication guard.
         *
         * @param string $name
         * @param array  $config
         *
         * @return \Illuminate\Auth\SessionGuard
         */
        Auth::extend('session', function ($app, $name, array $config) {
            $provider = auth()->createUserProvider($config['provider'] ?? null);

            $guard = new SessionGuard($name, $provider, $this->app['session.store']);

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($this->app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($this->app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });

        Auth::macro('logoutCurrentGuard', function () {
            $user = $this->user();

            $this->session->forget($this->session->getPrefixedGuard());

            $this->clearUserDataFromStorage();

            $this->session->migrate(true);

            // If we have an event dispatcher instance, we can fire off the logout event
            // so any further processing can be done. This allows the developer to be
            // listening for anytime a user signs out of this application manually.
            if (isset($this->events)) {
                $this->events->dispatch(new CurrentGuardLogout($this->name, $user));
            }

            // Once we have fired the logout event we will clear the users out of memory
            // so they are no longer available as the user is no longer considered as
            // being signed into this application and should not be available here.
            $this->user = null;

            $this->loggedOut = true;
        });
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
