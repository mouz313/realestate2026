<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    protected bool $withMail = false;

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($this->withMail && $notifiable->allowsChannel('email')) {
            $channels[] = 'mail';
        }

        if ($this->sms() !== '' && method_exists($notifiable, 'allowsChannel') && $notifiable->allowsChannel('sms', false) && ! empty($notifiable->phone)) {
            $channels[] = SmsChannel::class;
        }

        return $channels;
    }

    public function toSms(object $notifiable): string
    {
        return $this->sms();
    }

    protected function sms(): string
    {
        return '';
    }

    protected function statusText(?string $status): string
    {
        if ($status === null || $status === '') {
            return 'Unknown';
        }

        return str_replace('_', ' ', ucfirst($status));
    }
}
