<?php

namespace App\Notifications;

use App\Models\AgentPayout;
use Illuminate\Notifications\Messages\MailMessage;

class CommissionPayoutMade extends BaseNotification
{
    protected bool $withMail = true;

    public function __construct(public AgentPayout $agentPayout) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Commission payout made',
            'message' => 'Commission payout of PKR '.number_format($this->agentPayout->amount, 0).' has been processed.',
            'url' => route('agent-payouts.show', $this->agentPayout),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Commission payout processed')
            ->greeting('Hello,')
            ->line('Commission payout of PKR '.number_format($this->agentPayout->amount, 0).' has been processed.')
            ->action('View Payout', route('agent-payouts.show', $this->agentPayout));
    }

    protected function sms(): string
    {
        return 'Commission payout of PKR '.number_format($this->agentPayout->amount, 0).' has been processed. Thank you!';
    }
}
