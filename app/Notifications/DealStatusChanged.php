<?php

namespace App\Notifications;

use App\Models\Deal;
use Illuminate\Notifications\Messages\MailMessage;

class DealStatusChanged extends BaseNotification
{
    protected bool $withMail = true;

    public function __construct(
        public Deal $deal,
        public string $oldStatus,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Deal status updated',
            'message' => "Deal {$this->deal->deal_number} changed from {$this->statusText($this->oldStatus)} to {$this->statusText($this->deal->status)}.",
            'url' => route('deals.show', $this->deal),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Deal status updated — '.$this->deal->deal_number)
            ->greeting('Hello,')
            ->line("Deal {$this->deal->deal_number} status changed from {$this->statusText($this->oldStatus)} to {$this->statusText($this->deal->status)}.")
            ->action('View Deal', route('deals.show', $this->deal));
    }

    protected function sms(): string
    {
        return "Deal {$this->deal->deal_number} status: {$this->statusText($this->deal->status)}.";
    }
}
