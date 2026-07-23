<?php

namespace App\Mail;

use App\Models\Setting;

class MailSettings
{
    public static function apply(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (! empty($settings['mail_host'])) {
            config([
                'mail.default' => $settings['mail_driver'] ?? 'smtp',
                'mail.mailers.smtp.host' => $settings['mail_host'],
                'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
                'mail.mailers.smtp.username' => $settings['mail_username'] ?? null,
                'mail.mailers.smtp.password' => $settings['mail_password'] ?? null,
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
                'mail.from.address' => $settings['mail_from_address'] ?? 'noreply@example.com',
                'mail.from.name' => $settings['mail_from_name'] ?? ($settings['business_name'] ?? config('app.name')),
            ]);
        }
    }
}
