<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use App\Models\RentAgreement;
use Illuminate\Http\Request;

class RentAgreementController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $rentAgreements = RentAgreement::with(['tenant', 'property', 'owner'])
            ->when($agentId, fn ($q) => $q->whereHas('property', fn ($pq) => $pq->where('assigned_agent_id', $agentId)))
            ->latest()->paginate(15);

        return view('rent_agreements.index', compact('rentAgreements'));
    }

    public function create()
    {
        $properties = Property::where('transaction_type', 'rent')->orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('deal_number')->get();

        return view('rent_agreements.create', compact('properties', 'clients', 'deals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:clients,id',
            'owner_id' => 'required|exists:clients,id',
            'deal_id' => 'nullable|exists:deals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|max:50',
            'status' => 'required|string|in:active,expired,terminated,renewed',
            'deposit_received' => 'nullable|boolean',
            'deposit_returned' => 'nullable|boolean',
            'notice_period_days' => 'nullable|integer|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'rent_increase_percent' => 'nullable|numeric|min:0|max:100',
            'rent_increase_frequency' => 'nullable|string|in:yearly,none,monthly,quarterly',
            'terms' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:1000',
        ]);

        RentAgreement::create($request->only((new RentAgreement)->getFillable()));
        toastr()->success('Rent agreement added successfully.');

        return redirect()->route('rent-agreements.index');
    }

    public function show(RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $rentAgreement->load(['tenant', 'property', 'owner', 'deal']);

        return view('rent_agreements.show', compact('rentAgreement'));
    }

    public function edit(RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $properties = Property::where('transaction_type', 'rent')->orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('deal_number')->get();

        return view('rent_agreements.edit', compact('rentAgreement', 'properties', 'clients', 'deals'));
    }

    public function update(Request $request, RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:clients,id',
            'owner_id' => 'required|exists:clients,id',
            'deal_id' => 'nullable|exists:deals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|max:50',
            'status' => 'required|string|in:active,expired,terminated,renewed',
            'deposit_received' => 'nullable|boolean',
            'deposit_returned' => 'nullable|boolean',
            'notice_period_days' => 'nullable|integer|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'rent_increase_percent' => 'nullable|numeric|min:0|max:100',
            'rent_increase_frequency' => 'nullable|string|in:yearly,none,monthly,quarterly',
            'terms' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $rentAgreement->update($request->only((new RentAgreement)->getFillable()));
        toastr()->success('Rent agreement updated successfully.');

        return redirect()->route('rent-agreements.index');
    }

    public function destroy(RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $rentAgreement->delete();
        toastr()->success('Rent agreement deleted successfully.');

        return redirect()->route('rent-agreements.index');
    }
}
