<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Commission;
use App\Models\Deal;
use App\Models\InstallmentPlan;
use App\Models\Property;
use App\Models\Token;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::with(['property', 'buyer', 'seller', 'agent'])
            ->when($agentId, fn($q) => $q->where('agent_id', $agentId))
            ->latest()->paginate(15);
        return view('deals.index', compact('deals'));
    }

    public function create()
    {
        $properties = Property::where('status', 'available')->orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        return view('deals.create', compact('properties', 'clients', 'agents', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'agent_commission' => 'nullable|numeric|min:0',
            'agency_share' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:inquiry,visit_scheduled,offer_made,token_received,agreement_signed,in_progress,completed,cancelled',
            'agreement_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        $lastDeal = Deal::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastDeal ? $lastDeal->id + 1 : 1;
        $data['deal_number'] = 'DL-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        if (!empty($data['sale_price']) && !empty($data['commission_percentage'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        Deal::create($data);
        toastr()->success('Deal added successfully.');
        return redirect()->route('deals.index');
    }

    public function show(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $deal->load(['property', 'buyer', 'seller', 'agent', 'tokens', 'invoices', 'commissions', 'installmentPlan']);
        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        return view('deals.edit', compact('deal', 'properties', 'clients', 'agents', 'statuses'));
    }

    public function update(Request $request, Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'agent_commission' => 'nullable|numeric|min:0',
            'agency_share' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:inquiry,visit_scheduled,offer_made,token_received,agreement_signed,in_progress,completed,cancelled',
            'agreement_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        if (!empty($data['sale_price']) && !empty($data['commission_percentage'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        $deal->update($data);
        toastr()->success('Deal updated successfully.');
        return redirect()->route('deals.index');
    }

    public function destroy(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $deal->delete();
        toastr()->success('Deal deleted successfully.');
        return redirect()->route('deals.index');
    }
}
