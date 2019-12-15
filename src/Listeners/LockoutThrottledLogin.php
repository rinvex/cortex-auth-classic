<?php

declare(strict_types=1);

namespace Cortex\Auth\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cortex\Auth\Notifications\AuthenticationLockoutNotification;

class LockoutThrottledLogin implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create a new event listener instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Listen to the authentication lockout event.
     *
     * @param \Illuminate\Auth\Events\Lockout $event
     *
     * @return void
     */
    public function handle(Lockout $event): void
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

            ! $user || $user->notify(new AuthenticationLockoutNotification($event->request->ip(), $event->request->server('HTTP_USER_AGENT')));
        }
    }
}
