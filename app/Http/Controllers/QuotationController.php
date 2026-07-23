<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('client')->latest()->paginate(15);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        return view('quotations.create', compact('clients', 'items', 'taxRate', 'taxLabel', 'currency'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $quotationItems = [];

        foreach ($request->items as $line) {
            $lineTotal = $line['quantity'] * $line['unit_price'];
            $subtotal += $lineTotal;
            $quotationItems[] = [
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

        $quotation = Quotation::create([
            'client_id' => $request->client_id,
            'quote_number' => $this->generateQuoteNumber(),
            'status' => 'draft',
            'expiry_date' => $request->expiry_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        $quotation->items()->createMany($quotationItems);

        toastr()->success('Quotation created successfully.');
        return redirect()->route('quotations.index');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('client', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('quotations.show', compact('quotation', 'settings'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('items');
        $clients = Client::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        return view('quotations.edit', compact('quotation', 'clients', 'items', 'taxRate', 'taxLabel', 'currency'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $quotationItems = [];

        foreach ($request->items as $line) {
            $lineTotal = $line['quantity'] * $line['unit_price'];
            $subtotal += $lineTotal;
            $quotationItems[] = new QuotationItem([
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

        $quotation->update([
            'client_id' => $request->client_id,
            'expiry_date' => $request->expiry_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        $quotation->items()->delete();
        $quotation->items()->saveMany($quotationItems);

        toastr()->success('Quotation updated successfully.');
        return redirect()->route('quotations.index');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        toastr()->success('Quotation deleted successfully.');
        return redirect()->route('quotations.index');
    }

    public function pdf(Quotation $quotation)
    {
        $quotation->load('client', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'settings'));
        return $pdf->download('quotation-' . $quotation->quote_number . '.pdf');
    }

    public function markSent(Quotation $quotation)
    {
        if ($quotation->status !== 'draft') {
            toastr()->error('Only draft quotations can be marked as sent.');
            return back();
        }
        $quotation->update(['status' => 'sent']);

        if ($quotation->client->email) {
            try {
                \App\Mail\MailSettings::apply();
                \Illuminate\Support\Facades\Mail::to($quotation->client->email)->send(new \App\Mail\QuotationMail($quotation));
                toastr()->success('Quotation sent via email.');
            } catch (\Exception $e) {
                toastr()->warning('Quotation marked sent but email could not be delivered.');
            }
        }

        toastr()->success('Quotation marked as sent.');
        return back();
    }

    private function generateQuoteNumber(): string
    {
        $prefix = 'Q-';
        $last = Quotation::latest()->first();
        $number = $last ? intval(substr($last->quote_number, 2)) + 1 : 1;
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
