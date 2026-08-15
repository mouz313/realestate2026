<?php

namespace App\Notifications\Channels;

use App\Helpers\Sms;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $phone = $notifiable->phone ?? null;

        if (empty($phone)) {
            return;
        }

        Sms::send($phone, $notification->toSms($notifiable));
    }
}
