<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientResetPassword extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('portal.password.reset', ['token' => $this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('Reset Portal Password')
            ->line('You are receiving this email because you requested a portal password reset.')
            ->action('Reset Password', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request this, no further action is required.');
    }
}
