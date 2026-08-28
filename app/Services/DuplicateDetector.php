<?php

namespace App\Services;

use App\Models\CallLog;

class DuplicateDetector
{
    /**
     * Find call logs from the same phone on the same day.
     * Returns array of matches (may be empty).
     */
    public static function sameDay(?string $phone, ?int $excludeId = null): array
    {
        if (! $phone) {
            return [];
        }

        $normalized = self::normalize($phone);

        $query = CallLog::where(function ($q) use ($phone, $normalized) {
            $q->where('phone', $phone)
              ->orWhere('phone', $normalized)
              ->orWhere('alternate_phone', $phone)
              ->orWhere('alternate_phone', $normalized);
        })
        ->whereDate('created_at', today())
        ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
        ->with('assignedAgent')
        ->latest()
        ->get();

        return $query->all();
    }

    protected static function normalize(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
