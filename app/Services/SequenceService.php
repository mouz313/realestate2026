<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class SequenceService
{
    /**
     * Generate the next sequential code for a model.
     *
     * Each entity keeps its own generation strategy (some derive the next
     * number from the auto-increment id, others parse the previously issued
     * code) so behaviour stays identical to the original controller logic.
     */
    public static function property(): string
    {
        $last = Property::withTrashed()->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;

        return 'PR-'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public static function deal(): string
    {
        return DB::transaction(function () {
            $last = Deal::withTrashed()->lockForUpdate()->orderBy('id', 'desc')->first();
            $next = $last ? $last->id + 1 : 1;

            return 'DL-'.str_pad($next, 5, '0', STR_PAD_LEFT);
        });
    }

    public static function invoice(): string
    {
        $last = Invoice::latest()->first();
        $number = $last ? (int) substr($last->invoice_number, 4) + 1 : 1;

        return 'INV-'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public static function quotation(): string
    {
        $last = Quotation::latest()->first();
        $number = $last ? (int) substr($last->quote_number, 2) + 1 : 1;

        return 'Q-'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
