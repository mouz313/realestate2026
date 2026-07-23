<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Setting;

class DealController extends Controller
{
    public function index()
    {
        $client = $this->getClient();
        $deals = Deal::with(['property', 'agent'])
            ->where(function ($q) use ($client) {
                $q->where('buyer_id', $client->id)->orWhere('seller_id', $client->id);
            })
            ->latest()
            ->paginate(12);

        return view('portal.deals.index', compact('deals'));
    }

    public function show($id)
    {
        $client = $this->getClient();
        $deal = Deal::with(['property', 'buyer', 'seller', 'agent', 'coAgent', 'tokens', 'invoices', 'commissions', 'installmentPlan.installments'])
            ->where(function ($q) use ($client) {
                $q->where('buyer_id', $client->id)->orWhere('seller_id', $client->id);
            })
            ->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('portal.deals.show', compact('deal', 'settings'));
    }

    private function getClient()
    {
        $client = Client::find(session('client_id'));
        abort_if(! $client, 401);

        return $client;
    }
}
