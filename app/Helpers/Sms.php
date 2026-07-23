<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Sms
{
    public static function send(string $to, string $message): bool
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $provider = $settings['sms_provider'] ?? 'log';

        return match ($provider) {
            'twilio' => self::sendViaTwilio($to, $message, $settings),
            'connectix' => self::sendViaConnectix($to, $message, $settings),
            default => self::logSms($to, $message),
        };
    }

    public static function sendViaTwilio(string $to, string $message, array $settings): bool
    {
        Log::info('SMS via Twilio', ['to' => $to, 'message' => $message]);

        return true;
    }

    public static function sendViaConnectix(string $to, string $message, array $settings): bool
    {
        $username = $settings['sms_username'] ?? '';
        $password = $settings['sms_password'] ?? '';
        $sender = $settings['sms_sender'] ?? 'Agency';

        $to = ltrim($to, '+');

        try {
            $response = Http::post('https://connectix.com.pk/api/sms.php', [
                'username' => $username,
                'password' => $password,
                'sender' => $sender,
                'mobile' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('SMS via Connectix', ['response' => $response->body()]);

                return true;
            }

            Log::error('SMS failed via Connectix', ['response' => $response->body()]);

            return false;
        } catch (\Exception $e) {
            Log::error('SMS failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public static function logSms(string $to, string $message): bool
    {
        Log::info('SMS logged', ['to' => $to, 'message' => $message]);

        return true;
    }

    public static function visitReminder($visit): string
    {
        $date = $visit->scheduled_date->format('d M Y h:i A');

        return "Reminder: You have a property visit scheduled on {$date} at {$visit->property?->title}. Contact us for details.";
    }

    public static function tokenReceived($deal, $token): string
    {
        return 'Token/Bayana of PKR '.number_format($token->amount, 0)." received for Deal {$deal->deal_number}. Thank you!";
    }

    public static function installmentDue($installment): string
    {
        return "Reminder: Installment #{$installment->installment_no} of PKR ".number_format($installment->amount, 0)." is due on {$installment->due_date->format('d M Y')}. Please pay on time.";
    }

    public static function rentOverdue($agreement): string
    {
        return 'Reminder: Your rent of PKR '.number_format($agreement->rent_amount, 0).' is overdue. Please pay at the earliest to avoid late fees.';
    }
}
