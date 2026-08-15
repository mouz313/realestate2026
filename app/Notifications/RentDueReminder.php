<?php

namespace App\Notifications;

use App\Helpers\Sms;
use App\Models\RentPayment;

class RentDueReminder extends BaseNotification
{
    public function __construct(public RentPayment $rentPayment) {}

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Rent reminder',
            'message' => 'Your rent of PKR '.number_format($this->rentPayment->total_due, 0).' for '.$this->rentPayment->month_name.' is due.',
            'url' => route('portal.rent.payments'),
        ];
    }

    protected function sms(): string
    {
        return Sms::rentOverdue($this->rentPayment->rentAgreement);
    }
}
