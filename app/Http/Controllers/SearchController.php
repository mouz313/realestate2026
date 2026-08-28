<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\CallLog;
use App\Models\Client;
use App\Models\Commission;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\Quotation;
use App\Models\RentalRecord;
use App\Models\Token;
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
            ->orWhere('client_type', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Client',
                'label' => $c->name,
                'sub' => trim(($c->client_type ? ucfirst($c->client_type).' · ' : '').($c->email ?: $c->phone ?: ''), ' · '),
                'url' => route('clients.show', $c),
                'icon' => 'ti ti-users',
            ]);

        $properties = Property::where('title', 'like', "%{$q}%")
            ->orWhere('property_code', 'like', "%{$q}%")
            ->orWhere('city', 'like', "%{$q}%")
            ->orWhere('sector_town', 'like', "%{$q}%")
            ->orWhere('block', 'like', "%{$q}%")
            ->orWhere('location_address', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'Property',
                'label' => $p->title ?: $p->property_code,
                'sub' => trim(($p->city ?: '').($p->category ? ' · '.\App\Helpers\Status::categoryLabel($p->category) : ''), ' · '),
                'url' => route('properties.show', $p),
                'icon' => 'ti ti-building',
            ]);

        $deals = Deal::with(['buyer', 'property'])
            ->where('deal_number', 'like', "%{$q}%")
            ->orWhere('type', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('buyer', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->orWhereHas('seller', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->orWhereHas('property', fn ($q2) => $q2->where('title', 'like', "%{$q}%")->orWhere('property_code', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($d) => [
                'type' => 'Deal',
                'label' => $d->deal_number,
                'sub' => trim(($d->type ?: '').($d->buyer ? ' · Buyer: '.$d->buyer->name : ($d->seller ? ' · Seller: '.$d->seller->name : '')).($d->property ? ' · '.$d->property->title : ''), ' · '),
                'url' => route('deals.show', $d),
                'icon' => 'ti ti-businessplan',
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

        $calls = CallLog::with(['property', 'client'])
            ->where('name', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->orWhere('alternate_phone', 'like', "%{$q}%")
            ->orWhere('caller_role', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->orWhere('city', 'like', "%{$q}%")
            ->orWhere('location', 'like', "%{$q}%")
            ->orWhere('lead_source', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Call',
                'label' => $c->name.' — '.$c->phone,
                'sub' => trim(($c->caller_role ? ucfirst($c->caller_role) : '').($c->transaction_type ? ' · '.ucfirst($c->transaction_type) : '').($c->client ? ' · Client: '.$c->client->name : ''), ' · '),
                'url' => route('call-logs.show', $c),
                'icon' => 'ti ti-phone-call',
            ]);

        $agents = Agent::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->orWhere('role', 'like', "%{$q}%")
            ->orWhere('cnic', 'like', "%{$q}%")
            ->orWhere('license_number', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'type' => 'Agent',
                'label' => $a->name,
                'sub' => trim(($a->role ?: '').($a->email ? ' · '.$a->email : ''), ' · '),
                'url' => route('agents.show', $a),
                'icon' => 'ti ti-id-badge',
            ]);

        $rentals = RentalRecord::with(['property', 'tenant', 'landlord'])
            ->whereHas('property', fn ($q2) => $q2->where('title', 'like', "%{$q}%")->orWhere('property_code', 'like', "%{$q}%"))
            ->orWhereHas('tenant', fn ($q2) => $q2->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%"))
            ->orWhereHas('landlord', fn ($q2) => $q2->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%"))
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn ($r) => [
                'type' => 'Rental',
                'label' => $r->property?->title ?: ('Rental #'.$r->id),
                'sub' => trim(($r->status ?: '').($r->tenant ? ' · Tenant: '.$r->tenant->name : '').($r->landlord ? ' · Owner: '.$r->landlord->name : ''), ' · '),
                'url' => route('rental-records.show', $r),
                'icon' => 'ti ti-home-check',
            ]);

        $commissions = Commission::with(['deal', 'agent'])
            ->where('type', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('deal', fn ($q2) => $q2->where('deal_number', 'like', "%{$q}%"))
            ->orWhereHas('agent', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($cm) => [
                'type' => 'Commission',
                'label' => ($cm->type ? ucfirst($cm->type).' ' : '').'Commission #'.$cm->id,
                'sub' => trim(($cm->deal ? $cm->deal->deal_number : '').($cm->agent ? ' · '.$cm->agent->name : ''), ' · '),
                'url' => route('commissions.show', $cm),
                'icon' => 'ti ti-coin',
            ]);

        $tokens = Token::with(['deal'])
            ->where('reference_no', 'like', "%{$q}%")
            ->orWhere('payment_method', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('deal', fn ($q2) => $q2->where('deal_number', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($t) => [
                'type' => 'Token',
                'label' => $t->reference_no ?: ('Token #'.$t->id),
                'sub' => trim(($t->deal ? $t->deal->deal_number : '').($t->status ? ' · '.ucfirst($t->status) : ''), ' · '),
                'url' => route('tokens.show', $t),
                'icon' => 'ti ti-ticket',
            ]);

        $visits = PropertyVisit::with(['property', 'client', 'agent'])
            ->where('status', 'like', "%{$q}%")
            ->orWhere('feedback', 'like', "%{$q}%")
            ->orWhere('notes', 'like', "%{$q}%")
            ->orWhereHas('property', fn ($q2) => $q2->where('title', 'like', "%{$q}%")->orWhere('property_code', 'like', "%{$q}%"))
            ->orWhereHas('client', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->orWhereHas('agent', fn ($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->limit(5)
            ->get()
            ->map(fn ($v) => [
                'type' => 'Visit',
                'label' => 'Visit #'.$v->id.' — '.($v->property?->title ?: 'Property'),
                'sub' => trim(($v->status ?: '').($v->client ? ' · '.$v->client->name : '').($v->agent ? ' · Agent: '.$v->agent->name : ''), ' · '),
                'url' => route('property-visits.show', $v),
                'icon' => 'ti ti-walk',
            ]);

        $results = collect()
            ->concat($clients)
            ->concat($properties)
            ->concat($deals)
            ->concat($quotations)
            ->concat($invoices)
            ->concat($payments)
            ->concat($calls)
            ->concat($agents)
            ->concat($rentals)
            ->concat($commissions)
            ->concat($tokens)
            ->concat($visits);

        return response()->json($results);
    }
}
