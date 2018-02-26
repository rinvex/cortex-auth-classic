<?php

declare(strict_types=1);

namespace Cortex\Auth\Handlers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Cortex\Auth\Notifications\RegistrationSuccessNotification;
use Cortex\Auth\Notifications\AuthenticationLockoutNotification;

class GenericHandler
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Create a new GenericHandler instance.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen(Login::class, __CLASS__.'@login');
        $dispatcher->listen(Lockout::class, __CLASS__.'@lockout');
        $dispatcher->listen(Registered::class, __CLASS__.'@registered');
    }

    /**
     * Listen to the authentication lockout event.
     *
     * @param \Illuminate\Auth\Events\Lockout $event
     *
     * @return void
     */
    public function lockout(Lockout $event): void
    {
        if (config('cortex.auth.emails.throttle_lockout')) {
            switch ($event->request->route('accessarea')) {
                case 'managerarea':
                    $model = app('cortex.auth.manager');
                    break;
                case 'adminarea':
                    $model = app('cortex.auth.admin');
                    break;
                case 'frontarea':
                case 'tenantarea':
                default:
                    $model = app('cortex.auth.member');
                    break;
            }

            $user = get_login_field($loginfield = $event->request->get('loginfield')) === 'email'
                ? $model::where('email', $loginfield)->first()
                : $model::where('username', $loginfield)->first();

            ! $user || $user->notify(new AuthenticationLockoutNotification($event->request));
        }
    }

    /**
     * Listen to the authentication login event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     *
     * @return void
     */
    public function login(Login $event): void
    {
        ! config('cortex.auth.persistence') === 'single' || $event->user->sessions()->delete();
    }

    /**
     * Listen to the register success event.
     *
     * @param \Illuminate\Contracts\Auth\Registered $event
     *
     * @return void
     */
    public function registered(Registered $event): void
    {
        ! config('cortex.auth.emails.welcome') || $event->user->notify(new RegistrationSuccessNotification());
    }
}
