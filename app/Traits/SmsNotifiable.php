<?php

namespace App\Traits;

use App\Helpers\Sms;
use App\Models\Client;

trait SmsNotifiable
{
    public function notifyClient(Client $client, string $template, ...$params): void
    {
        if (! $client->phone) {
            return;
        }

        $message = match ($template) {
            'visit_reminder' => Sms::visitReminder($params[0]),
            'token_received' => Sms::tokenReceived($params[0], $params[1]),
            'installment_due' => Sms::installmentDue($params[0]),
            'rent_overdue' => Sms::rentOverdue($params[0]),
            default => $params[0] ?? '',
        };

        if ($message) {
            Sms::send($client->phone, $message);
        }
    }
}
