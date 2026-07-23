<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Quotation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        $clients = Client::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Client',
                'label' => $c->name,
                'sub' => $c->email,
                'url' => route('clients.show', $c),
                'icon' => 'ti ti-users',
            ]);

        $items = Item::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($i) => [
                'type' => 'Item',
                'label' => $i->name,
                'sub' => $i->unit_price ? number_format($i->unit_price, 2) : '',
                'url' => route('items.edit', $i),
                'icon' => 'ti ti-package',
            ]);

        $quotations = Quotation::with('client')
            ->where('quote_number', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('client', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($qt) => [
                'type' => 'Quotation',
                'label' => $qt->quote_number,
                'sub' => $qt->client?->name,
                'url' => route('quotations.show', $qt),
                'icon' => 'ti ti-file-description',
            ]);

        $invoices = Invoice::with('client')
            ->where('invoice_number', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('payment_status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('client', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($inv) => [
                'type' => 'Invoice',
                'label' => $inv->invoice_number,
                'sub' => $inv->client?->name,
                'url' => route('invoices.show', $inv),
                'icon' => 'ti ti-file-invoice',
            ]);

        $payments = Payment::with('invoice.client')
            ->where('reference', 'like', "%{$q}%")
            ->orWhere('method', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('invoice', fn ($query) => $query->where('invoice_number', 'like', "%{$q}%"))
            ->orWhereHas('invoice.client', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'Payment',
                'label' => $p->reference ?: '#' . $p->id,
                'sub' => $p->invoice?->invoice_number . ' — ' . number_format($p->amount, 2),
                'url' => route('invoices.show', $p->invoice),
                'icon' => 'ti ti-currency-dollar',
            ]);

        $results = collect()
            ->concat($clients)
            ->concat($items)
            ->concat($quotations)
            ->concat($invoices)
            ->concat($payments);

        return response()->json($results);
    }
}
