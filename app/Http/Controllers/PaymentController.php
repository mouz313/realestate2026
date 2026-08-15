<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['invoice.client', 'rentAgreement.tenant'])->latest()->paginate(15);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('payments.index', compact('payments', 'settings'));
    }

    public function edit(Payment $payment)
    {
        if (! $payment->invoice_id) {
            toastr()->warning('Deposit payments are managed from the rent agreement page.');

            return redirect()->route('rent-agreements.show', $payment->rent_agreement_id);
        }

        $payment->load('invoice');

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        if (! $payment->invoice_id) {
            toastr()->warning('Deposit payments are managed from the rent agreement page.');

            return redirect()->route('rent-agreements.show', $payment->rent_agreement_id);
        }

        $invoice = $payment->invoice;

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:255',
            'paid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'paid_date' => $request->paid_date,
            'notes' => $request->notes,
        ]);

        $paidAmount = $invoice->payments()->sum('amount');
        $invoice->update([
            'paid_amount' => $paidAmount,
            'payment_status' => $paidAmount >= $invoice->total ? 'paid' : ($paidAmount > 0 ? 'partial' : 'pending'),
        ]);

        toastr()->success('Payment updated successfully.');

        return redirect()->route('invoices.show', $invoice);
    }

    public function destroy(Payment $payment)
    {
        if (! $payment->invoice_id) {
            toastr()->warning('Deposit payments are managed from the rent agreement page.');

            return redirect()->route('rent-agreements.show', $payment->rent_agreement_id);
        }

        $invoice = $payment->invoice;

        $payment->delete();

        $paidAmount = $invoice->payments()->sum('amount');
        $invoice->update([
            'paid_amount' => $paidAmount,
            'payment_status' => $paidAmount >= $invoice->total ? 'paid' : ($paidAmount > 0 ? 'partial' : 'pending'),
        ]);

        toastr()->success('Payment deleted successfully.');

        return redirect()->route('invoices.show', $invoice);
    }

    public function exportExcel()
    {
        [$headers, $rows] = \App\Exports\PaymentExport::build();

        return \App\Services\ExcelWriter::stream('payments-'.date('Y-m-d').'.xlsx', $headers, $rows);
    }
}
