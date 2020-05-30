<?php

declare(strict_types=1);

namespace Cortex\Auth\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cortex\Auth\Notifications\RegistrationSuccessNotification;

class SendWelcomeEmail implements ShouldQueue
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
     * Listen to the register success event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     *
     * @return void
     */
    public function handle(Registered $event): void
    {
        ! config('cortex.auth.emails.welcome') || $event->user->notify(new RegistrationSuccessNotification());
    }
}
