<?php

declare(strict_types=1);

namespace Cortex\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AuthenticationLockoutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The client ip address.
     *
     * @var string
     */
    public $ip;

    /**
     * The client agent.
     *
     * @var string
     */
    public $agent;

    /**
     * Create a notification instance.
     *
     * @param string $ip
     * @param string $agent
     */
    public function __construct(string $ip, string $agent)
    {
        $this->ip = $ip;
        $this->agent = $agent;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(trans('cortex/auth::emails.auth.lockout.subject'))
            ->line(trans('cortex/auth::emails.auth.lockout.intro', [
                'created_at' => now(),
                'ip' => $this->ip,
                'agent' => $this->agent,
            ]))
            ->line(trans('cortex/auth::emails.auth.lockout.outro'));
    }
}
