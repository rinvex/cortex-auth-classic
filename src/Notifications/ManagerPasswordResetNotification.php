<?php

declare(strict_types=1);

namespace Cortex\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ManagerPasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset expiration date.
     *
     * @var int
     */
    public $expiration;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @param int    $expiration
     */
    public function __construct(string $token, int $expiration)
    {
        $this->token = $token;
        $this->expiration = $expiration;
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
        $email = $notifiable->getEmailForPasswordReset();
        $link = route('managerarea.passwordreset.reset')."?email={$email}&expiration={$this->expiration}&token={$this->token}";

        return (new MailMessage())
            ->subject(trans('cortex/auth::emails.passwordreset.request.subject'))
            ->line(trans('cortex/auth::emails.passwordreset.request.intro', ['expire' => now()->createFromTimestamp($this->expiration)->diffForHumans()]))
            ->action(trans('cortex/auth::emails.passwordreset.request.action'), $link)
            ->line(trans('cortex/auth::emails.passwordreset.request.outro'));
    }
}
