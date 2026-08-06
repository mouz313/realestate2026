<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('rent:sync-overdue')->daily();
Schedule::command('rent:expire-agreements')->daily();
Schedule::command('rent:send-notifications')->daily();
Schedule::command('invoices:generate-recurring')->daily();
