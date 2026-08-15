<?php

namespace App\Traits;

trait HasNotificationPrefs
{
    public function allowsChannel(string $channel, bool $default = true): bool
    {
        if (property_exists($this, 'notification_prefs') && ! empty($this->notification_prefs)) {
            $prefs = is_array($this->notification_prefs) ? $this->notification_prefs : json_decode($this->notification_prefs, true);

            return $prefs[$channel] ?? $default;
        }

        return $default;
    }
}