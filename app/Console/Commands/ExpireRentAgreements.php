<?php

namespace App\Console\Commands;

use App\Models\RentAgreement;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireRentAgreements extends Command
{
    protected $signature = 'rent:expire-agreements';

    protected $description = 'Auto-expire rent agreements past their end_date';

    public function handle(): int
    {
        $expired = RentAgreement::where('status', 'active')
            ->where('end_date', '<', Carbon::today())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} agreement(s).");

        return Command::SUCCESS;
    }
}
