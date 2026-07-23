<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        return view('invoices.create', compact('clients', 'items', 'taxRate', 'taxLabel', 'currency'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $invoiceItems = [];

        foreach ($request->items as $line) {
            $lineTotal = $line['quantity'] * $line['unit_price'];
            $subtotal += $lineTotal;
            $invoiceItems[] = [
                'item_id' => $line['item_id'] ?? null,
                'item_name' => $line['item_name'],
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit' => $line['unit'] ?? null,
                'unit_price' => $line['unit_price'],
                'line_total' => $lineTotal,
            ];
        }

        $taxRate = $request->tax_rate;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $invoice = Invoice::create([
            'client_id' => $request->client_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'status' => 'unpaid',
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'paid_amount' => 0,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        $invoice->items()->createMany($invoiceItems);

        toastr()->success('Invoice created successfully.');
        return redirect()->route('invoices.index');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $clients = Client::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        return view('invoices.edit', compact('invoice', 'clients', 'items', 'taxRate', 'taxLabel', 'currency'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $invoiceItems = [];

        foreach ($request->items as $line) {
            $lineTotal = $line['quantity'] * $line['unit_price'];
            $subtotal += $lineTotal;
            $invoiceItems[] = new InvoiceItem([
                'item_id' => $line['item_id'] ?? null,
                'item_name' => $line['item_name'],
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit' => $line['unit'] ?? null,
                'unit_price' => $line['unit_price'],
                'line_total' => $lineTotal,
            ]);
        }

        $taxRate = $request->tax_rate;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $invoice->update([
            'client_id' => $request->client_id,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        $invoice->items()->delete();
        $invoice->items()->saveMany($invoiceItems);

        toastr()->success('Invoice updated successfully.');
        return redirect()->route('invoices.index');
    }

    public function convertFromQuotation(Quotation $quotation)
    {
        if (in_array($quotation->status, ['rejected', 'invoiced'])) {
            toastr()->error('This quotation cannot be converted.');
            return back();
        }

        $quotation->load('client', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();
        $paymentTerms = (int) ($settings['payment_terms'] ?? 30);

        $invoice = Invoice::create([
            'quotation_id' => $quotation->id,
            'client_id' => $quotation->client_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'status' => 'unpaid',
            'due_date' => now()->addDays($paymentTerms),
            'subtotal' => $quotation->subtotal,
            'tax_rate' => $quotation->tax_rate,
            'tax_amount' => $quotation->tax_amount,
            'total' => $quotation->total,
            'paid_amount' => 0,
            'payment_status' => 'pending',
            'notes' => $quotation->notes,
        ]);

        foreach ($quotation->items as $item) {
            $invoice->items()->create([
                'item_name' => $item->item_name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ]);
        }

        $quotation->update(['status' => 'invoiced']);

        if ($invoice->client->email) {
            try {
                \App\Mail\MailSettings::apply();
                \Illuminate\Support\Facades\Mail::to($invoice->client->email)->send(new \App\Mail\InvoiceMail($invoice));
            } catch (\Exception $e) {
                // Email failure shouldn't block the conversion
            }
        }

        toastr()->success('Invoice created from quotation.');
        return redirect()->route('invoices.show', $invoice);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'quotation', 'items', 'payments');
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('invoices.show', compact('invoice', 'settings'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'settings'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $remaining = $invoice->total - $invoice->paid_amount;

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $remaining,
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:255',
            'paid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment = $invoice->payments()->create([
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

        toastr()->success('Payment recorded successfully.');
        return back();
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        toastr()->success('Invoice deleted successfully.');
        return redirect()->route('invoices.index');
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $last = Invoice::latest()->first();
        $number = $last ? intval(substr($last->invoice_number, 4)) + 1 : 1;
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
