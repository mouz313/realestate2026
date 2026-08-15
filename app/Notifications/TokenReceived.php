<?php

namespace App\Notifications;

use App\Helpers\Sms;
use App\Models\Token;
use Illuminate\Notifications\Messages\MailMessage;

class TokenReceived extends BaseNotification
{
    protected bool $withMail = true;

    public function __construct(public Token $token) {}

    public function toDatabase(object $notifiable): array
    {
        $dealNumber = $this->token->deal?->deal_number ?? 'N/A';

        return [
            'title' => 'Token received',
            'message' => 'Token of PKR '.number_format($this->token->amount, 0)." received for Deal {$dealNumber}.",
            'url' => route('tokens.show', $this->token),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dealNumber = $this->token->deal?->deal_number ?? 'N/A';

        return (new MailMessage)
            ->subject('Token received — '.$dealNumber)
            ->greeting('Hello,')
            ->line('Token of PKR '.number_format($this->token->amount, 0)." received for Deal {$dealNumber}.")
            ->action('View Token', route('tokens.show', $this->token));
    }

    protected function sms(): string
    {
        return Sms::tokenReceived($this->token->deal, $this->token);
    }
}
