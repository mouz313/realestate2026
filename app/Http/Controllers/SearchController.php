<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Property;
use App\Models\Quotation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        if (! $q || strlen($q) < 2) {
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

        $contacts = Contact::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->orWhere('property_title', 'like', "%{$q}%")
            ->orWhere('city', 'like', "%{$q}%")
            ->orWhere('message', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Enquiry',
                'label' => $c->name ?: ($c->property_title ?: 'Enquiry'),
                'sub' => trim(($c->lead_source ?: '').($c->city ? ' · '.$c->city : ''), ' · '),
                'url' => route('contacts.show', $c),
                'icon' => 'ti ti-message-circle',
            ]);

        $properties = Property::where('title', 'like', "%{$q}%")
            ->orWhere('property_code', 'like', "%{$q}%")
            ->orWhere('city', 'like', "%{$q}%")
            ->orWhere('sector_town', 'like', "%{$q}%")
            ->orWhere('block', 'like', "%{$q}%")
            ->orWhere('location_address', 'like', "%{$q}%")
            ->orWhere('type', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'Property',
                'label' => $p->title ?: $p->property_code,
                'sub' => trim(($p->city ?: '').($p->type ? ' · '.$p->type : ''), ' · '),
                'url' => route('properties.show', $p),
                'icon' => 'ti ti-building',
            ]);

        $deals = Deal::with(['buyer', 'property'])
            ->where('deal_number', 'like', "%{$q}%")
            ->orWhere('type', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('buyer', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->orWhereHas('property', fn ($q2) => $q2->where('title', 'like', "%{$q}%")->orWhere('property_code', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($d) => [
                'type' => 'Deal',
                'label' => $d->deal_number,
                'sub' => trim(($d->type ?: '').($d->buyer ? ' · '.$d->buyer->name : ($d->property ? ' · '.$d->property->title : '')), ' · '),
                'url' => route('deals.show', $d),
                'icon' => 'ti ti-handshake',
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
                'label' => $p->reference ?: '#'.$p->id,
                'sub' => $p->invoice?->invoice_number.' — '.number_format($p->amount, 2),
                'url' => route('invoices.show', $p->invoice),
                'icon' => 'ti ti-currency-dollar',
            ]);

        $posts = Post::where('title', 'like', "%{$q}%")
            ->orWhere('slug', 'like', "%{$q}%")
            ->orWhere('excerpt', 'like', "%{$q}%")
            ->orWhere('body', 'like', "%{$q}%")
            ->orWhere('seo_title', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'Post',
                'label' => $p->title,
                'sub' => $p->is_published ? 'Published' : 'Draft',
                'url' => route('posts.edit', $p),
                'icon' => 'ti ti-article',
            ]);

        $results = collect()
            ->concat($clients)
            ->concat($contacts)
            ->concat($properties)
            ->concat($deals)
            ->concat($quotations)
            ->concat($invoices)
            ->concat($payments)
            ->concat($posts);

        return response()->json($results);
    }
}
