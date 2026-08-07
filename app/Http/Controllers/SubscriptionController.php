<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['company', 'package', 'verifiedBy'])
            ->orderByDesc('id')
            ->get();

        $pendingCount = $subscriptions->where('status', Subscription::STATUS_PENDING)->count();

        return view('admin.subscriptions.index', compact('subscriptions', 'pendingCount'));
    }

    public function pending()
    {
        $subscriptions = Subscription::with(['company', 'package'])
            ->whereIn('status', [Subscription::STATUS_PENDING])
            ->orderByDesc('id')
            ->get();

        return view('admin.subscriptions.pending', compact('subscriptions'));
    }

    public function approve(Request $request, Subscription $subscription)
    {
        $request->validate([
            'note' => 'nullable|string',
        ]);

        $package = $subscription->package;

        $days = $package->interval === 'year' ? 365 : 30;
        $isTrial = $package->trial_days > 0 && (float) $subscription->amount_paid <= 0;

        $startDate = $subscription->started_at ?? now();

        if ($isTrial) {
            $endsAt = Carbon::instance($startDate)->addDays($package->trial_days);
            $status = Subscription::STATUS_TRIAL;
        } else {
            $endsAt = Carbon::instance($startDate)->addDays($days);
            $status = Subscription::STATUS_ACTIVE;
        }

        $subscription->update([
            'status' => $status,
            'started_at' => $startDate,
            'ends_at' => $endsAt,
            'trial_ends_at' => $isTrial ? $endsAt : null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'note' => $request->input('note') ?? $subscription->note,
        ]);

        // Make this the company's current subscription.
        $subscription->company->update(['current_subscription_id' => $subscription->id]);

        toastr()->success("Subscription for {$subscription->company->name} approved and set as current.", 'Approved');

        return back();
    }

    public function block(Request $request, Subscription $subscription)
    {
        $request->validate([
            'block_reason' => 'required|string|max:1000',
        ]);

        $subscription->update([
            'status' => Subscription::STATUS_SUSPENDED,
            'block_reason' => $request->input('block_reason'),
            'ends_at' => null,
            'trial_ends_at' => null,
        ]);

        // If this was the company's current subscription, suspend the company too.
        if ($subscription->company->current_subscription_id === $subscription->id) {
            $subscription->company->update([
                'current_subscription_id' => null,
                'is_active' => false,
            ]);
        }

        toastr()->success('Subscription blocked and company suspended.', 'Blocked');

        return back();
    }

    public function unblock(Subscription $subscription)
    {
        // Reactivate the company with this (previously blocked) subscription.
        $package = $subscription->package;
        $days = $package->interval === 'year' ? 365 : 30;
        $endsAt = now()->addDays($days);

        $subscription->update([
            'status' => $package->trial_days > 0 && (float) $subscription->amount_paid <= 0
                ? Subscription::STATUS_TRIAL
                : Subscription::STATUS_ACTIVE,
            'block_reason' => null,
            'started_at' => $subscription->started_at ?? now(),
            'ends_at' => $endsAt,
            'trial_ends_at' => $subscription->status === Subscription::STATUS_TRIAL ? $endsAt : null,
            'verified_at' => now(),
        ]);

        $subscription->company->update([
            'is_active' => true,
            'current_subscription_id' => $subscription->id,
        ]);

        toastr()->success("{$subscription->company->name} has been unblocked.", 'Unblocked');

        return back();
    }
}
