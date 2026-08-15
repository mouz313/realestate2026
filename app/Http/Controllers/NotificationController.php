<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->paginate(15);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        $notification = $request->user()->notifications();

        if ($request->filled('id')) {
            $notification = $notification->where('id', $request->id);
        }

        if ($request->filled('_all')) {
            $notification->update(['read_at' => now()]);
        } elseif ($request->filled('id')) {
            $notification->update(['read_at' => now()]);
        }

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['status' => 'ok']);
        }

        return back();
    }
}
