<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')->latest();

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('description', 'like', "%{$s}%");
        }

        $activities = $query->paginate(25)->withQueryString();

        return view('admin.activity-log', compact('activities'));
    }
}
