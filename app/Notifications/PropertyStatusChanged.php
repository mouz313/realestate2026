<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Notifications\Messages\MailMessage;

class PropertyStatusChanged extends BaseNotification
{
    protected bool $withMail = true;

    public function __construct(
        public Property $property,
        public string $oldStatus,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Property status updated',
            'message' => "Property '{$this->property->title}' status changed from {$this->statusText($this->oldStatus)} to {$this->statusText($this->property->status)}.",
            'url' => route('properties.show', $this->property),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property status updated — '.$this->property->title)
            ->greeting('Hello,')
            ->line("Property '{$this->property->title}' status changed from {$this->statusText($this->oldStatus)} to {$this->statusText($this->property->status)}.")
            ->action('View Property', route('properties.show', $this->property));
    }
}
