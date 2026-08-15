<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\DealStatusChanged;
use App\Notifications\RentPaymentReceived;
use PHPUnit\Framework\TestCase;

class NotificationChannelTest extends TestCase
{
    protected function fakeNotifiable(array $prefs, ?string $phone = '03001234567'): User
    {
        $notifiable = new User();
        $notifiable->notification_prefs = $prefs;
        if ($phone !== null) {
            $notifiable->phone = $phone;
        }

        return $notifiable;
    }

    public function test_db_channel_always_present(): void
    {
        $notifiable = $this->fakeNotifiable([], null);
        $notification = new RentPaymentReceived(new \App\Models\RentPayment());

        $this->assertContains('database', $notification->via($notifiable));
    }

    public function test_email_included_when_preferred(): void
    {
        $notifiable = $this->fakeNotifiable(['email' => true]);
        $notification = new DealStatusChanged(new \App\Models\Deal(), 'inquiry');

        $this->assertContains('mail', $notification->via($notifiable));
    }

    public function test_email_excluded_when_disabled(): void
    {
        $notifiable = $this->fakeNotifiable(['email' => false]);
        $notification = new DealStatusChanged(new \App\Models\Deal(), 'inquiry');

        $this->assertNotContains('mail', $notification->via($notifiable));
    }

    public function test_sms_included_when_preferred_and_phone_present(): void
    {
        $notifiable = $this->fakeNotifiable(['sms' => true]);
        $notification = new RentPaymentReceived(new \App\Models\RentPayment());

        $this->assertContains(SmsChannel::class, $notification->via($notifiable));
    }

    public function test_sms_excluded_without_phone(): void
    {
        $notifiable = $this->fakeNotifiable(['sms' => true], null);
        $notification = new RentPaymentReceived(new \App\Models\RentPayment());

        $this->assertNotContains(SmsChannel::class, $notification->via($notifiable));
    }
}
