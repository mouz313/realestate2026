<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\RentalRecord;
use App\Notifications\RentalVerificationNotification;
use Illuminate\Console\Command;

class VerifyRentalStatusCommand extends Command
{
    protected $signature = 'rent:verify-status';

    protected $description = 'Email agencies to re-verify rented properties at 3/6/12 months.';

    public function handle(): int
    {
        $milestones = [3, 6, 12];

        foreach (Company::all() as $company) {
            session(['company_id' => $company->id]);

            $records = RentalRecord::where('status', 'active')->get();

            foreach ($records as $record) {
                if (empty($record->start_date)) {
                    continue;
                }

                $months = now()->diffInMonths(\Carbon\Carbon::parse($record->start_date));
                $sent = $record->reminders_sent ?? [];

                foreach ($milestones as $m) {
                    if ($months >= $m && ! isset($sent[$m])) {
                        notify_company(
                            $company,
                            RentalVerificationNotification::class,
                            ['record' => $record, 'milestone' => $m]
                        );

                        $sent[$m] = now()->toDateString();
                        $record->reminders_sent = $sent;
                        $record->last_verification_sent_at = now();
                        $record->save();

                        $this->info("Notified company #{$company->id} for record #{$record->id} at {$m} months.");
                    }
                }
            }
        }

        session()->forget('company_id');

        return self::SUCCESS;
    }
}
