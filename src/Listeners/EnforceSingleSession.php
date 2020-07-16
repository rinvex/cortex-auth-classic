<?php

declare(strict_types=1);

namespace Cortex\Auth\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnforceSingleSession implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

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
     * @TODO #1: Review this method!
     *      Check => \Cortex\Auth\Http\Middleware\AuthenticateSession
     *      Also check => logoutOtherDevices method
     *
     * @TODO #2: Add support for Laravel Passport login `AccessTokenCreated`
     *
     * Listen to the authentication login event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     *
     * @return void
     */
    public function handle(Login $event): void
    {
        config('cortex.auth.persistence') !== 'single' || $event->user->sessions()->delete();
    }
}
