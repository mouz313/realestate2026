<?php

namespace App\Notifications;

use App\Models\RentalRecord;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalVerificationNotification extends Notification
{
    public function __construct(
        public RentalRecord $record,
        public int $milestone,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $property = $this->record->property;
        $tenant = $this->record->tenant;

        return (new MailMessage)
            ->subject("Rental Verification Reminder ({$this->milestone} months)")
            ->greeting('Hello,')
            ->line("This is a reminder to re-verify the rental status of a property in your portfolio ({$this->milestone} months since the rental started).")
            ->line('Property: ' . ($property?->title ?? 'Unknown'))
            ->line('Tenant: ' . ($tenant?->name ?? 'Unknown'))
            ->line('Start Date: ' . ($this->record->start_date ? $this->record->start_date->format('d M Y') : 'N/A'))
            ->line('End Date: ' . ($this->record->end_date ? $this->record->end_date->format('d M Y') : 'N/A'))
            ->line('Please verify whether this property is still rented or is now available, and update its status in the system accordingly.');
    }

    public function toDatabase(object $notifiable): array
    {
        $property = $this->record->property;

        return [
            'title' => "Rental verification due ({$this->milestone} months)",
            'message' => "Please verify the rental status of '" . ($property?->title ?? 'Unknown') . "' (" . ($this->record->tenant?->name ?? 'Unknown') . ").",
            'url' => route('rental-records.show', $this->record),
        ];
    }
}
