<?php

return [
    'token' => env('CRON_TOKEN', 'skyline-cron-2026'),

    'jobs' => [
        'rent-reminders' => [
            'command' => 'rent:remind',
            'name' => 'Rent Reminders',
            'description' => 'Send SMS rent-due reminders to tenants due soon.',
            'schedule' => 'Daily at 09:00',
            'options' => [],
        ],
        'rent-sync-overdue' => [
            'command' => 'rent:sync-overdue',
            'name' => 'Sync Overdue Rent Payments',
            'description' => 'Mark rent payments as overdue once past their due date.',
            'schedule' => 'Daily',
            'options' => [],
        ],
        'rent-expire-agreements' => [
            'command' => 'rent:expire-agreements',
            'name' => 'Expire Rent Agreements',
            'description' => 'Move rent agreements to expired status when their term ends.',
            'schedule' => 'Daily',
            'options' => [],
        ],
        'rent-send-notifications' => [
            'command' => 'rent:send-notifications',
            'name' => 'Send Rent Notifications',
            'description' => 'Notify tenants/owners about rent dues and notices.',
            'schedule' => 'Daily',
            'options' => [],
        ],
        'invoices-recurring' => [
            'command' => 'invoices:generate-recurring',
            'name' => 'Generate Recurring Invoices',
            'description' => 'Auto-generate invoices for recurring/subscription billing.',
            'schedule' => 'Daily',
            'options' => [],
        ],
        'backup' => [
            'command' => 'backup:run',
            'name' => 'Application Backup',
            'description' => 'Run the database/files backup job.',
            'schedule' => 'Weekly (Sun 02:00)',
            'options' => [],
        ],
    ],
];
