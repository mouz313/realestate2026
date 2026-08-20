<?php

namespace App\Console\Commands;

use App\Models\CallLog;
use App\Models\Company;
use App\Notifications\CallFollowupNotification;
use Illuminate\Console\Command;

class CallFollowupCommand extends Command
{
    protected $signature = 'call:followups';

    protected $description = 'Notify agents of due call follow-ups.';

    public function handle(): int
    {
        $companies = Company::withoutGlobalScopes()->get();

        $today = today();
        $since = $today->copy()->subDays(2);

        foreach ($companies as $company) {
            session(['company_id' => $company->id]);

            $due = CallLog::whereNotNull('follow_up_date')
                ->where('follow_up_date', '<=', $today)
                ->whereNotIn('status', ['converted', 'lost'])
                ->get();

            foreach ($due as $callLog) {
                if ($callLog->follow_up_date->lt($since)) {
                    continue;
                }

                notify_company($company, CallFollowupNotification::class, ['call' => $callLog]);
            }
        }

        $this->info('Call follow-up notifications processed.');

        return self::SUCCESS;
    }
}
