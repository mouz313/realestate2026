<?php

namespace App\Http\Controllers;

use App\Helpers\QrHelper;
use App\Mail\MailSettings;
use App\Mail\QuotationMail;
use App\Models\Client;
use App\Models\ItemTemplate;
use App\Models\Property;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with('client', 'property')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('quote_number', 'like', "%{$s}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$s}%"));
            });
        }

        $quotations = $query->paginate(15);
        $clients = Client::orderBy('name')->get(['id', 'name']);

        return view('quotations.index', compact('quotations', 'clients'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $properties = Property::whereIn('status', ['available', 'pending'])->orderBy('title')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        $templates = ItemTemplate::where('is_active', true)->orderBy('name')->get();

        return view('quotations.create', compact('clients', 'properties', 'taxRate', 'taxLabel', 'currency', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'property_id' => 'nullable|exists:properties,id',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
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
                'item_name' => $line['item_name'],
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit' => $line['unit'] ?? null,
                'unit_price' => $line['unit_price'],
                'line_total' => $lineTotal,
            ];
        }

        $discountType = $request->discount_type;
        $discountValue = (float) ($request->discount_value ?? 0);
        $discountAmount = $discountType === 'percentage'
            ? $subtotal * ($discountValue / 100)
            : $discountValue;
        $discountAmount = min($discountAmount, $subtotal);

        $taxRate = $request->tax_rate;
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($taxRate / 100);
        $total = $taxableAmount + $taxAmount;

        $quotation = Quotation::create([
            'client_id' => $request->client_id,
            'property_id' => $request->property_id,
            'quote_number' => $this->generateQuoteNumber(),
            'status' => 'draft',
            'expiry_date' => $request->expiry_date,
            'subtotal' => $subtotal,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
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
        $quotation->load('client', 'property', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('quotations.show', compact('quotation', 'settings'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('items');
        $clients = Client::orderBy('name')->get();
        $properties = Property::whereIn('status', ['available', 'pending'])->orderBy('title')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $taxRate = $settings['tax_rate'] ?? 0;
        $taxLabel = $settings['tax_label'] ?? 'GST';
        $currency = $settings['currency'] ?? 'PKR';
        $templates = ItemTemplate::where('is_active', true)->orderBy('name')->get();

        return view('quotations.edit', compact('quotation', 'clients', 'properties', 'taxRate', 'taxLabel', 'currency', 'templates'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'property_id' => 'nullable|exists:properties,id',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
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
                'item_name' => $line['item_name'],
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit' => $line['unit'] ?? null,
                'unit_price' => $line['unit_price'],
                'line_total' => $lineTotal,
            ]);
        }

        $discountType = $request->discount_type;
        $discountValue = (float) ($request->discount_value ?? 0);
        $discountAmount = $discountType === 'percentage'
            ? $subtotal * ($discountValue / 100)
            : $discountValue;
        $discountAmount = min($discountAmount, $subtotal);

        $taxRate = $request->tax_rate;
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($taxRate / 100);
        $total = $taxableAmount + $taxAmount;

        $quotation->update([
            'client_id' => $request->client_id,
            'property_id' => $request->property_id,
            'expiry_date' => $request->expiry_date,
            'subtotal' => $subtotal,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
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
        $quotation->load('client', 'property', 'items');
        $settings = Setting::pluck('value', 'key')->toArray();

        $verifyUrl = route('quotations.show', $quotation);
        $qrCode = QrHelper::pngDataUri($verifyUrl);

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'settings', 'qrCode'));

        return $pdf->download('quotation-'.$quotation->quote_number.'.pdf');
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
                MailSettings::apply();
                Mail::to($quotation->client->email)->send(new QuotationMail($quotation));
                toastr()->success('Quotation sent via email.');
            } catch (\Exception $e) {
                toastr()->warning('Quotation marked sent but email could not be delivered.');
            }
        }

        toastr()->success('Quotation marked as sent.');

        return back();
    }

    public function versions(Quotation $quotation)
    {
        $quotation->load('versions');

        return view('quotations.versions', compact('quotation'));
    }

    private function generateQuoteNumber(): string
    {
        $prefix = 'Q-';
        $last = Quotation::latest()->first();
        $number = $last ? intval(substr($last->quote_number, 2)) + 1 : 1;

        return $prefix.str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
