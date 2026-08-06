<?php

namespace App\Console\Commands;

use App\Models\RentPayment;
use Illuminate\Console\Command;

class SyncOverdueRentPayments extends Command
{
    protected $signature = 'rent:sync-overdue';

    protected $description = 'Sync overdue rent payments and calculate late fees';

    public function handle(): int
    {
        $overduePayments = RentPayment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        $count = 0;
        foreach ($overduePayments as $payment) {
            $payment->syncLateFee();
            $count++;
        }

        $this->info("Synced {$count} overdue payment(s).");

        return Command::SUCCESS;
    }
}
