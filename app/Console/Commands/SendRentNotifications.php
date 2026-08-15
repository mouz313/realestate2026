<?php

namespace App\Console\Commands;

use App\Mail\RentExpiryReminder;
use App\Mail\RentOverdueReminder;
use App\Models\RentAgreement;
use App\Models\RentPayment;
use App\Notifications\RentDueReminder;
use App\Notifications\RentExpiryReminder as RentExpiryReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRentNotifications extends Command
{
    protected $signature = 'rent:send-notifications';

    protected $description = 'Send overdue reminders and expiry notifications for rent agreements';

    public function handle(): int
    {
        $sent = 0;

        $overduePayments = RentPayment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['rentAgreement.tenant'])
            ->get();

        foreach ($overduePayments as $payment) {
            $tenant = $payment->rentAgreement?->tenant;
            if ($tenant && $tenant->email) {
                try {
                    Mail::to($tenant->email)->send(new RentOverdueReminder($payment));
                    $sent++;
                } catch (\Exception $e) {
                    $this->error("Failed to send overdue reminder for payment #{$payment->id}: {$e->getMessage()}");
                }

                $tenant->notify(new RentDueReminder($payment));
                $sent++;
            }
        }

        $expiringAgreements = RentAgreement::where('status', 'active')
            ->where('end_date', Carbon::today()->addDays(30))
            ->where('end_date', '>', Carbon::today())
            ->with(['tenant', 'owner', 'property'])
            ->get();

        foreach ($expiringAgreements as $agreement) {
            if ($agreement->tenant && $agreement->tenant->email) {
                try {
                    Mail::to($agreement->tenant->email)->send(new RentExpiryReminder($agreement, 'tenant'));
                    $sent++;
                } catch (\Exception $e) {
                    $this->error("Failed to send expiry reminder to tenant for agreement #{$agreement->id}: {$e->getMessage()}");
                }

                $agreement->tenant->notify(new RentExpiryReminderNotification($agreement, 'tenant'));
                $sent++;
            }
            if ($agreement->owner && $agreement->owner->email) {
                try {
                    Mail::to($agreement->owner->email)->send(new RentExpiryReminder($agreement, 'owner'));
                    $sent++;
                } catch (\Exception $e) {
                    $this->error("Failed to send expiry reminder to owner for agreement #{$agreement->id}: {$e->getMessage()}");
                }

                $agreement->owner->notify(new RentExpiryReminderNotification($agreement, 'owner'));
                $sent++;
            }
        }

        $this->info("Sent {$sent} notification(s).");

        return Command::SUCCESS;
    }
}
