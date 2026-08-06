<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use App\Models\RentAgreement;
use App\Models\RentNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'payment_frequency' => 'nullable|string|in:monthly,quarterly,half-yearly',
            'status' => 'required|string|in:active,expired,terminated,renewed,pending',
            'deposit_received' => 'nullable|boolean',
            'deposit_returned' => 'nullable|boolean',
            'notice_period_days' => 'nullable|integer|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'rent_increase_percent' => 'nullable|numeric|min:0|max:100',
            'rent_increase_frequency' => 'nullable|string|in:yearly,none,monthly,quarterly',
            'terms' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:1000',
            'agreement_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
        ]);

        $data = $request->only((new RentAgreement)->getFillable());
        if ($request->hasFile('agreement_doc')) {
            $data['agreement_doc'] = $request->file('agreement_doc')->store('rent-agreements', 'public');
        }

        $rentAgreement = RentAgreement::create($data);
        $rentAgreement->generateSchedule();
        toastr()->success('Rent agreement added successfully.');

        return redirect()->route('rent-agreements.index');
    }

    public function show(RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $rentAgreement->load(['tenant', 'property', 'owner', 'deal', 'renewedFrom', 'renewals', 'rentNotices.tenant']);

        $rentAgreement->rentPayments->each(function ($payment) {
            $payment->syncLateFee();
        });

        return view('rent_agreements.show', compact('rentAgreement'));
    }

    public function renew(Request $request, RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $request->validate([
            'new_start_date' => 'required|date|after:today',
            'new_end_date' => 'required|date|after:new_start_date',
            'new_rent_amount' => 'required|numeric|min:0',
        ]);

        $rentAgreement->update(['status' => 'renewed']);

        $newAgreement = RentAgreement::create([
            'renewed_from_id' => $rentAgreement->id,
            'deal_id' => $rentAgreement->deal_id,
            'property_id' => $rentAgreement->property_id,
            'tenant_id' => $rentAgreement->tenant_id,
            'owner_id' => $rentAgreement->owner_id,
            'start_date' => $request->new_start_date,
            'end_date' => $request->new_end_date,
            'rent_amount' => $request->new_rent_amount,
            'security_deposit' => $rentAgreement->security_deposit,
            'deposit_received' => $rentAgreement->deposit_received,
            'deposit_returned' => false,
            'notice_period_days' => $rentAgreement->notice_period_days,
            'late_fee_per_day' => $rentAgreement->late_fee_per_day,
            'rent_increase_percent' => $rentAgreement->rent_increase_percent,
            'rent_increase_frequency' => $rentAgreement->rent_increase_frequency,
            'payment_frequency' => $rentAgreement->payment_frequency,
            'terms' => $rentAgreement->terms,
            'notes' => $rentAgreement->notes,
            'status' => 'active',
        ]);
        $newAgreement->generateSchedule();

        toastr()->success('Agreement renewed. New agreement #'.$newAgreement->id.' created.');

        return redirect()->route('rent-agreements.show', $newAgreement);
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
            'payment_frequency' => 'nullable|string|in:monthly,quarterly,half-yearly',
            'status' => 'required|string|in:active,expired,terminated,renewed,pending',
            'deposit_received' => 'nullable|boolean',
            'deposit_returned' => 'nullable|boolean',
            'notice_period_days' => 'nullable|integer|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'rent_increase_percent' => 'nullable|numeric|min:0|max:100',
            'rent_increase_frequency' => 'nullable|string|in:yearly,none,monthly,quarterly',
            'terms' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:1000',
            'agreement_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
        ]);

        $data = $request->only((new RentAgreement)->getFillable());
        if ($request->hasFile('agreement_doc')) {
            if ($rentAgreement->agreement_doc && Storage::disk('public')->exists($rentAgreement->agreement_doc)) {
                Storage::disk('public')->delete($rentAgreement->agreement_doc);
            }
            $data['agreement_doc'] = $request->file('agreement_doc')->store('rent-agreements', 'public');
        } else {
            unset($data['agreement_doc']);
        }

        $rentAgreement->update($data);
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

    public function settleDeposit(Request $request, RentAgreement $rentAgreement)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        $request->validate([
            'deposit_deductions' => 'required|numeric|min:0|max:'.$rentAgreement->security_deposit,
            'deposit_deduction_notes' => 'nullable|string|max:1000',
        ]);

        $rentAgreement->update([
            'deposit_returned' => true,
            'deposit_returned_date' => now()->toDateString(),
            'deposit_deductions' => $request->deposit_deductions,
            'deposit_deduction_notes' => $request->deposit_deduction_notes,
        ]);

        toastr()->success('Security deposit settled successfully.');

        return back();
    }

    public function respondNotice(Request $request, RentAgreement $rentAgreement, RentNotice $rentNotice)
    {
        $this->authorizeAgentAccess($rentAgreement, 'property');
        abort_unless($rentNotice->rent_agreement_id === $rentAgreement->id, 404);

        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $rentNotice->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        $statusText = $request->status === 'accepted' ? 'accepted' : 'rejected';
        toastr()->success("Notice {$statusText} successfully.");

        return back();
    }
}
