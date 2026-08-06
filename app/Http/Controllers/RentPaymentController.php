<?php

namespace App\Http\Controllers;

use App\Mail\RentPaymentConfirmation;
use App\Models\RentAgreement;
use App\Models\RentPayment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = RentAgreement::with(['property', 'tenant', 'owner', 'rentPayments'])
            ->where('status', 'active');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$s}%"))
                    ->orWhereHas('tenant', fn ($tq) => $tq->where('name', 'like', "%{$s}%"));
            });
        }

        $agreements = $query->orderByDesc('start_date')->get();

        $agreements->each(function ($agreement) {
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $currentPayment = $agreement->rentPayments
                ->firstWhere('month', $currentMonth)
                ->firstWhere('year', $currentYear);

            $agreement->current_payment = $currentPayment;
            $agreement->total_paid = (float) $agreement->rentPayments->where('status', 'paid')->sum('amount');
            $agreement->total_pending = (float) $agreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
            $agreement->total_late_fees = (float) $agreement->rentPayments->sum('late_fee');
            $agreement->months_paid = $agreement->rentPayments->where('status', 'paid')->count();
            $agreement->total_months = $agreement->rentPayments->count();
        });

        $totalPending = RentPayment::pending()->sum('total_due');
        $totalOverdue = RentPayment::overdue()->sum('total_due');
        $collectedThisMonth = RentPayment::where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('amount');

        return view('rent-payments.index', compact('agreements', 'totalPending', 'totalOverdue', 'collectedThisMonth'));
    }

    public function show($id)
    {
        $rentAgreement = RentAgreement::with(['property', 'tenant', 'owner', 'rentPayments'])->findOrFail($id);

        $rentAgreement->rentPayments->each(function ($payment) {
            $payment->syncLateFee();
        });

        $rentAgreement->load('rentPayments');

        $totalPaid = (float) $rentAgreement->rentPayments->where('status', 'paid')->sum('amount');
        $totalPending = (float) $rentAgreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
        $totalLateFee = (float) $rentAgreement->rentPayments->sum('late_fee');
        $monthsPaid = $rentAgreement->rentPayments->where('status', 'paid')->count();
        $totalMonths = $rentAgreement->rentPayments->count();

        $payments = $rentAgreement->rentPayments->sortBy('year')->sortBy('month')->values();

        return view('rent-payments.show', compact('rentAgreement', 'payments', 'totalPaid', 'totalPending', 'totalLateFee', 'monthsPaid', 'totalMonths'));
    }

    public function edit($id)
    {
        $rentPayment = RentPayment::with('rentAgreement')->findOrFail($id);

        return view('rent-payments.edit', compact('rentPayment'));
    }

    public function update(Request $request, $id)
    {
        $rentPayment = RentPayment::findOrFail($id);
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2050',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $rentPayment->update([
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
            'total_due' => $request->amount + $rentPayment->late_fee,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        toastr()->success('Rent payment updated.');

        return redirect()->route('rent-payments.show', $rentPayment->rent_agreement_id);
    }

    public function destroy($id)
    {
        $rentPayment = RentPayment::findOrFail($id);
        $agreementId = $rentPayment->rent_agreement_id;
        $rentPayment->delete();

        toastr()->success('Rent payment deleted.');

        return redirect()->route('rent-payments.show', $agreementId);
    }

    public function updateStatus(Request $request, RentPayment $rentPayment)
    {
        $request->validate([
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'paid_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $rentPayment->update([
            'status' => 'paid',
            'paid_date' => $request->paid_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no,
            'notes' => $request->notes,
        ]);

        $rentPayment->rentAgreement->generateNextMonth();

        if ($rentPayment->rentAgreement->tenant && $rentPayment->rentAgreement->tenant->email) {
            try {
                Mail::to($rentPayment->rentAgreement->tenant->email)->send(new RentPaymentConfirmation($rentPayment));
            } catch (\Exception $e) {
                // Silently fail — don't block payment recording
            }
        }

        toastr()->success('Rent payment marked as paid.');

        return back();
    }

    public function waive(RentPayment $rentPayment)
    {
        $rentPayment->update(['status' => 'waived']);

        $rentPayment->rentAgreement->generateNextMonth();

        toastr()->success('Rent payment waived.');

        return back();
    }

    public function regenerateSchedule(RentAgreement $rentAgreement)
    {
        $rentAgreement->generateSchedule();
        toastr()->success('Payment schedule regenerated.');

        return back();
    }

    public function generateNextMonth(RentAgreement $rentAgreement)
    {
        $rentAgreement->generateNextMonth();
        toastr()->success('Next month payment generated.');

        return back();
    }

    public function receipt(RentPayment $rentPayment)
    {
        $rentPayment->load(['rentAgreement.property', 'rentAgreement.tenant', 'rentAgreement.owner']);
        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('pdf.rent-receipt', compact('rentPayment', 'settings'));

        return $pdf->stream("rent-receipt-{$rentPayment->id}.pdf");
    }
}
