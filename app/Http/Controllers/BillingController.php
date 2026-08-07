<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BillingController extends Controller
{
    public function index()
    {
        $company = current_company();
        $active = $company->activeSubscription();
        $subscriptions = $company->subscriptions()->with('package')->orderByDesc('id')->get();
        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();

        $usage = [
            'employees' => $company->users()->count(),
            'clients' => \App\Models\Client::where('company_id', $company->id)->count(),
            'properties' => \App\Models\Property::where('company_id', $company->id)->count(),
        ];

        return view('billing.index', compact('company', 'active', 'subscriptions', 'packages', 'usage'));
    }

    public function checkout(Package $package)
    {
        $company = current_company();
        $current = $company->activeSubscription();
        $isUpgrade = $current && $package->price > $current->package->price;
        $isDowngrade = $current && $package->price < $current->package->price;

        return view('billing.checkout', compact('package', 'company', 'current', 'isUpgrade', 'isDowngrade'));
    }

    public function storeCheckout(Request $request, Package $package)
    {
        $company = current_company();
        $current = $company->activeSubscription();

        // Downgrade is not allowed.
        if ($current && $package->price < $current->package->price) {
            toastr()->error('Downgrading to a cheaper package is not allowed. Choose a higher or equal plan.', 'Downgrade blocked');

            return back();
        }

        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'proof' => $package->isFree() ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' : 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $proof = $validated['proof']
            ? $request->file('proof')->store('subscriptions/proofs', 'public')
            : null;

        DB::transaction(function () use ($company, $package, $current, $validated, $proof) {
            Subscription::create([
                'company_id' => $company->id,
                'package_id' => $package->id,
                'previous_subscription_id' => $current ? $current->id : null,
                'status' => Subscription::STATUS_PENDING,
                'amount_paid' => $validated['amount_paid'],
                'currency' => $package->currency,
                'proof_path' => $proof,
                'note' => $validated['note'],
            ]);
        });

        toastr()->success('Your subscription request has been submitted. We will verify your payment and activate it shortly.', 'Submitted for approval');

        return redirect()->route('billing.index');
    }

    public function cancelPending(Subscription $subscription)
    {
        $company = current_company();

        if ($subscription->company_id !== (int) $company->id || ! $subscription->isExpired() && $subscription->status !== Subscription::STATUS_PENDING) {
            abort(403);
        }

        if ($subscription->proof_path) {
            Storage::disk('public')->delete($subscription->proof_path);
        }

        $subscription->delete();
        toastr()->success('Pending request cancelled.', 'Cancelled');

        return redirect()->route('billing.index');
    }
}
