<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\RentAgreement;
use App\Models\RentPayment;

class OwnerRentController extends Controller
{
    public function dashboard()
    {
        $clientId = session('client_id');

        $agreements = RentAgreement::where('owner_id', $clientId)
            ->with(['property', 'tenant', 'rentPayments'])
            ->get();

        $activeCount = $agreements->where('status', 'active')->count();
        $totalIncome = 0;
        $totalPending = 0;

        foreach ($agreements as $agreement) {
            $totalIncome += (float) $agreement->rentPayments->where('status', 'paid')->sum('amount');
            $totalPending += (float) $agreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
        }

        $recentPayments = RentPayment::whereHas('rentAgreement', fn ($q) => $q->where('owner_id', $clientId))
            ->where('status', 'paid')
            ->with('rentAgreement.property')
            ->orderByDesc('paid_date')
            ->limit(5)
            ->get();

        return view('portal.rent.owner-dashboard', compact('agreements', 'activeCount', 'totalIncome', 'totalPending', 'recentPayments'));
    }

    public function properties()
    {
        $clientId = session('client_id');
        $agreements = RentAgreement::where('owner_id', $clientId)
            ->with(['property', 'tenant', 'rentPayments'])
            ->latest()
            ->get();

        return view('portal.rent.owner-properties', compact('agreements'));
    }

    public function property(RentAgreement $rentAgreement)
    {
        $this->ensureOwner($rentAgreement);
        $rentAgreement->load(['property', 'tenant', 'rentPayments']);

        $rentAgreement->rentPayments->each(function ($payment) {
            $payment->syncLateFee();
        });
        $rentAgreement->load('rentPayments');

        $payments = $rentAgreement->rentPayments->sortBy('year')->sortBy('month')->values();
        $totalPaid = (float) $rentAgreement->rentPayments->where('status', 'paid')->sum('amount');
        $totalPending = (float) $rentAgreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');

        return view('portal.rent.owner-property-detail', compact('rentAgreement', 'payments', 'totalPaid', 'totalPending'));
    }

    public function income()
    {
        $clientId = session('client_id');
        $agreements = RentAgreement::where('owner_id', $clientId)
            ->with(['property', 'rentPayments'])
            ->get();

        $monthlyIncome = [];
        foreach ($agreements as $agreement) {
            foreach ($agreement->rentPayments->where('status', 'paid') as $payment) {
                $key = $payment->year.'-'.str_pad($payment->month, 2, '0', STR_PAD_LEFT);
                if (! isset($monthlyIncome[$key])) {
                    $monthlyIncome[$key] = ['month' => $key, 'total' => 0, 'breakdown' => []];
                }
                $monthlyIncome[$key]['total'] += (float) $payment->amount;
                $monthlyIncome[$key]['breakdown'][] = [
                    'property' => $agreement->property?->title ?? 'N/A',
                    'amount' => (float) $payment->amount,
                ];
            }
        }
        krsort($monthlyIncome);

        return view('portal.rent.owner-income', ['monthlyIncome' => array_values($monthlyIncome)]);
    }

    public function tenants()
    {
        $clientId = session('client_id');
        $agreements = RentAgreement::where('owner_id', $clientId)
            ->with(['tenant', 'property'])
            ->where('status', 'active')
            ->get();

        return view('portal.rent.owner-tenants', compact('agreements'));
    }

    private function ensureOwner(RentAgreement $agreement): void
    {
        if ($agreement->owner_id !== session('client_id')) {
            abort(403);
        }
    }
}
