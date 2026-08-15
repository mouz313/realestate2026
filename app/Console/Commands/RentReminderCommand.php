<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\RentPayment;
use App\Models\Setting;
use App\Services\SmsService;
use Illuminate\Console\Command;

class RentReminderCommand extends Command
{
    protected $signature = 'rent:remind {--days=3 : Remind payments due within this many days} {--dry-run : List without sending}';

    protected $description = 'Send SMS rent-due reminders for pending/overdue rent payments';

    public function handle(): int
    {
        $sms = new SmsService;

        if (! $sms->isConfigured()) {
            $this->warn('SMS is not configured. Set sms_provider / sms_username in Settings, or use --dry-run to preview.');

            if (! $this->option('dry-run')) {
                return self::FAILURE;
            }
        }

        $days = (int) $this->option('days');
        $windowEnd = now()->addDays($days)->endOfDay();

        $payments = RentPayment::with(['rentAgreement.tenant', 'rentAgreement.property'])
            ->where('status', 'pending')
            ->where('due_date', '<=', $windowEnd)
            ->orderBy('due_date')
            ->get();

        if ($payments->isEmpty()) {
            $this->info('No pending rent payments due within '.$days.' days.');

            return self::SUCCESS;
        }

        $agency = Setting::where('key', 'brand_name')->value('value') ?: config('app.name');
        $sent = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($payments as $payment) {
            $tenant = $payment->rentAgreement?->tenant;
            $property = $payment->rentAgreement?->property;

            if (! $tenant || ! $tenant->phone) {
                $skipped++;

                continue;
            }

            $message = sprintf(
                'Dear %s, your rent for %s%s of Rs %s is due on %s. Please pay promptly to avoid late fees. -%s',
                $tenant->name,
                $payment->month_name,
                $property ? ' ('.$property->title.' )' : '',
                number_format($payment->total_due, 0),
                $payment->due_date->format('d M Y'),
                $agency
            );

            if ($this->option('dry-run')) {
                $this->line("[dry-run] To {$tenant->phone}: {$message}");
                $sent++;

                continue;
            }

            if ($sms->send($tenant->phone, $message)) {
                $sent++;
                Activity::create([
                    'description' => 'Rent reminder SMS sent to '.$tenant->name.' for '.$payment->month_name,
                    'log_name' => 'rent_reminder',
                ]);
            } else {
                $failed++;
            }
        }

        $this->info("Reminders: {$sent} sent, {$failed} failed, {$skipped} skipped (no tenant phone).");

        return self::SUCCESS;
    }
}
