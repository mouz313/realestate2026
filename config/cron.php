<?php

return [
    // Must be set per deployment via CRON_TOKEN in .env. No hardcoded default:
    // if unset, the cron endpoint rejects all requests (see CronController).
    'token' => env('CRON_TOKEN'),

    'jobs' => [
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
        'rent-verify-status' => [
            'command' => 'rent:verify-status',
            'name' => 'Verify Rental Status',
            'description' => 'Email the agency to confirm rented properties are still rented (staggered 6/9/12 months).',
            'schedule' => 'Daily (staggered per record)',
            'options' => [],
        ],
        'call-followups' => [
            'command' => 'call:followups',
            'name' => 'Call Follow-ups',
            'description' => 'Remind agents of due call-log follow-ups.',
            'schedule' => 'Daily',
            'options' => [],
        ],
    ],
];
