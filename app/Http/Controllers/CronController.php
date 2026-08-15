<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function run(Request $request, string $job)
    {
        $jobs = config('cron.jobs', []);

        if (! isset($jobs[$job])) {
            abort(404, 'Unknown cron job.');
        }

        if ($request->query('token') !== config('cron.token')) {
            abort(403, 'Invalid token.');
        }

        $exit = Artisan::call($jobs[$job]['command'], $jobs[$job]['options'] ?? []);
        $output = Artisan::output();

        return response()->json([
            'job' => $job,
            'command' => $jobs[$job]['command'],
            'exit_code' => $exit,
            'output' => $output,
        ]);
    }
}
