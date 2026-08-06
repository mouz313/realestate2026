<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\RentAgreement;
use App\Models\RentNotice;
use App\Models\RentPayment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TenantRentController extends Controller
{
    public function dashboard()
    {
        $clientId = session('client_id');

        $agreements = RentAgreement::where('tenant_id', $clientId)
            ->with(['property', 'rentPayments'])
            ->get();

        $activeCount = $agreements->where('status', 'active')->count();
        $totalPaid = 0;
        $totalPending = 0;

        foreach ($agreements as $agreement) {
            $totalPaid += (float) $agreement->rentPayments->where('status', 'paid')->sum('amount');
            $totalPending += (float) $agreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
        }

        $recentPayments = RentPayment::whereHas('rentAgreement', fn ($q) => $q->where('tenant_id', $clientId))
            ->orderByDesc('paid_date')
            ->limit(5)
            ->get();

        return view('portal.rent.tenant-dashboard', compact('agreements', 'activeCount', 'totalPaid', 'totalPending', 'recentPayments'));
    }

    public function agreements()
    {
        $clientId = session('client_id');
        $agreements = RentAgreement::where('tenant_id', $clientId)
            ->with(['property', 'owner'])
            ->latest()
            ->get();

        return view('portal.rent.tenant-agreements', compact('agreements'));
    }

    public function agreement(RentAgreement $rentAgreement)
    {
        $this->ensureTenant($rentAgreement);
        $rentAgreement->load(['property', 'owner', 'rentPayments']);

        $rentAgreement->rentPayments->each(function ($payment) {
            $payment->syncLateFee();
        });
        $rentAgreement->load('rentPayments');

        $payments = $rentAgreement->rentPayments->sortBy('year')->sortBy('month')->values();
        $totalPaid = (float) $rentAgreement->rentPayments->where('status', 'paid')->sum('amount');
        $totalPending = (float) $rentAgreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');

        return view('portal.rent.tenant-agreement-detail', compact('rentAgreement', 'payments', 'totalPaid', 'totalPending'));
    }

    public function payments()
    {
        $clientId = session('client_id');
        $payments = RentPayment::whereHas('rentAgreement', fn ($q) => $q->where('tenant_id', $clientId))
            ->with('rentAgreement.property')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('portal.rent.tenant-payments', compact('payments'));
    }

    public function receipt(RentPayment $rentPayment)
    {
        $this->ensureTenantAgreement($rentPayment);
        $rentPayment->load(['rentAgreement.property', 'rentAgreement.tenant', 'rentAgreement.owner']);
        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('pdf.rent-receipt', compact('rentPayment', 'settings'));

        return $pdf->stream("rent-receipt-{$rentPayment->id}.pdf");
    }

    public function submitNotice(Request $request, RentAgreement $rentAgreement)
    {
        $this->ensureTenant($rentAgreement);

        if ($rentAgreement->status !== 'active') {
            toastr()->error('Notice can only be submitted for active agreements.');

            return back();
        }

        $noticePeriodDays = $rentAgreement->notice_period_days ?? 30;
        $minMoveOutDate = Carbon::today()->addDays($noticePeriodDays);

        $request->validate([
            'move_out_date' => "required|date|after_or_equal:{$minMoveOutDate->toDateString()}",
            'reason' => 'nullable|string|max:1000',
        ]);

        $hasPending = RentNotice::where('rent_agreement_id', $rentAgreement->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            toastr()->error('You already have a pending notice for this agreement.');

            return back();
        }

        RentNotice::create([
            'rent_agreement_id' => $rentAgreement->id,
            'tenant_id' => session('client_id'),
            'notice_date' => now()->toDateString(),
            'move_out_date' => $request->move_out_date,
            'notice_type' => 'tenant',
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        toastr()->success('Notice submitted successfully. We will review it shortly.');

        return back();
    }

    private function ensureTenant(RentAgreement $agreement): void
    {
        if ($agreement->tenant_id !== session('client_id')) {
            abort(403);
        }
    }

    private function ensureTenantAgreement(RentPayment $payment): void
    {
        $payment->load('rentAgreement');
        if ($payment->rentAgreement->tenant_id !== session('client_id')) {
            abort(403);
        }
    }
}
