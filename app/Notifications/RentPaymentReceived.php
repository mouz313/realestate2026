<?php

namespace App\Notifications;

use App\Models\RentPayment;

class RentPaymentReceived extends BaseNotification
{
    public function __construct(public RentPayment $rentPayment) {}

    public function toDatabase(object $notifiable): array
    {
        $amount = (float) ($this->rentPayment->total_due ?? 0);

        return [
            'title' => 'Rent payment received',
            'message' => 'Payment of '.$this->rentPayment->month_name.' — PKR '.number_format($amount, 0).' received.',
            'url' => route('portal.rent.payments'),
        ];
    }

    protected function sms(): string
    {
        $amount = (float) ($this->rentPayment->total_due ?? 0);

        return 'Payment of PKR '.number_format($amount, 0)." received for {$this->rentPayment->month_name}. Thank you!";
    }
}
