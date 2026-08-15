<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Notifications\Messages\MailMessage;

class PortalAccessGranted extends BaseNotification
{
    protected bool $withMail = true;

    public function __construct(public Client $client) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Portal access granted',
            'message' => "Portal access has been granted for {$this->client->name}.",
            'url' => route('portal.login'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Portal access granted')
            ->greeting('Hello,')
            ->line('Your portal access has been set up. You can now log in at the agency portal.')
            ->action('Open Portal', route('portal.login'));
    }
}
