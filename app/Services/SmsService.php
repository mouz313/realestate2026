<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function isConfigured(): bool
    {
        $provider = $this->get('sms_provider');

        return in_array($provider, ['twilio', 'http'], true)
            && ! empty($this->get('sms_username'));
    }

    public function send(string $to, string $message): bool
    {
        $to = $this->normalizePhone($to);

        if (! $to) {
            return false;
        }

        $provider = $this->get('sms_provider');

        if ($provider === 'twilio') {
            return $this->sendTwilio($to, $message);
        }

        if ($provider === 'http' || filter_var($provider, FILTER_VALIDATE_URL)) {
            return $this->sendGeneric($to, $message);
        }

        return false;
    }

    protected function sendTwilio(string $to, string $message): bool
    {
        $sid = $this->get('sms_username');
        $token = $this->get('sms_password');
        $from = $this->get('sms_sender');

        if (! $sid || ! $token || ! $from) {
            return false;
        }

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'To' => $to,
                'From' => $from,
                'Body' => $message,
            ]);

        if (! $response->successful()) {
            Log::warning('SMS (Twilio) failed', ['to' => $to, 'status' => $response->status()]);
        }

        return $response->successful();
    }

    protected function sendGeneric(string $to, string $message): bool
    {
        $endpoint = $this->get('sms_provider');

        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post($endpoint, [
                'api_key' => $this->get('sms_username'),
                'api_secret' => $this->get('sms_password'),
                'sender' => $this->get('sms_sender'),
                'to' => $to,
                'message' => $message,
            ]);

        if (! $response->successful()) {
            Log::warning('SMS (generic) failed', ['to' => $to, 'status' => $response->status()]);
        }

        return $response->successful();
    }

    protected function normalizePhone(?string $phone): string
    {
        if (! $phone) {
            return '';
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '92'.substr($phone, 1);
        } elseif (strlen($phone) === 10) {
            $phone = '92'.$phone;
        }

        return $phone;
    }

    protected function get(string $key, $default = null)
    {
        $value = Setting::where('key', $key)->value('value');

        return ($value === null || $value === '') ? $default : $value;
    }
}
