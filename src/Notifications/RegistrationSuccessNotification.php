<?php

declare(strict_types=1);

namespace Cortex\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject(trans('cortex/auth::emails.register.welcome.subject'))
            ->line(
                config('cortex.auth.registration.moderated')
                ? trans('cortex/auth::emails.register.welcome.intro_moderation')
                : trans('cortex/auth::emails.register.welcome.intro_default')
            );
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => 'listeners',
        ];
    }
}
