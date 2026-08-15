<?php

namespace App\Notifications;

use App\Models\RentAgreement;

class RentExpiryReminder extends BaseNotification
{
    public function __construct(
        public RentAgreement $rentAgreement,
        public string $for,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        $endDate = $this->rentAgreement->end_date?->format('d M Y') ?? 'N/A';

        return [
            'title' => 'Rent agreement expiring',
            'message' => 'Your rent agreement expires on '.$endDate.'. Please plan for renewal or move-out.',
            'url' => route('portal.rent.dashboard'),
        ];
    }

    protected function sms(): string
    {
        $propertyTitle = $this->rentAgreement->property?->title ?? 'N/A';
        $endDate = $this->rentAgreement->end_date?->format('d M Y') ?? 'N/A';

        return "Your rent agreement expires on {$endDate} for {$propertyTitle}. Please plan for renewal or move-out.";
    }
}
