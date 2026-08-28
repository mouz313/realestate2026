<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Commission;
use App\Models\Deal;
use App\Services\CommissionCalculator;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $commissions = Commission::with(['deal', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->latest()->paginate(15);

        return view('commissions.index', compact('commissions'));
    }

    public function create()
    {
        $deals = Deal::where('status', 'completed')->orderBy('deal_number')->get();
        $agents = Agent::orderBy('name')->get();
        $types = ['percentage', 'fixed'];

        return view('commissions.create', compact('deals', 'agents', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'agent_id' => 'required|exists:agents,id',
            'type' => 'required|string|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();
        $data['company_id'] = current_company_id();

        // Per-row authorization: an agent may only create commissions for
        // themselves, and only admins with the payout permission may set a
        // non-pending status (prevents status spoofing via mass-assignment).
        if (auth()->user()->isAgent()) {
            $data['agent_id'] = auth()->user()->agent_id;
        }
        if (! auth()->user()->hasPermission('mark_commission_paid')) {
            $data['status'] = 'pending';
        }

        // Split the entered total fee 90/10 (agency/agent) per the locked spec.
        $split = CommissionCalculator::split((float) ($data['amount'] ?? 0));
        $data['agency_amount'] = $split['agency_amount'];
        $data['agent_amount'] = $split['agent_amount'];
        $data['amount'] = $split['agent_amount']; // amount column stores the agent payout
        $data['source'] = Deal::find($data['deal_id'])?->type ?? 'sale';

        Commission::create($data);
        toastr()->success('Commission added successfully.');

        return redirect()->route('commissions.index');
    }

    public function edit(Commission $commission)
    {
        $deals = Deal::where('status', 'completed')->orderBy('deal_number')->get();
        $agents = Agent::orderBy('name')->get();
        $types = ['percentage', 'fixed'];

        return view('commissions.edit', compact('commission', 'deals', 'agents', 'types'));
    }

    public function update(Request $request, Commission $commission)
    {
        $this->authorizeAgentAccess($commission);
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'agent_id' => 'required|exists:agents,id',
            'type' => 'required|string|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        // Mirror store() authorization: an agent may only edit their own
        // commission, and only admins with the payout permission may set a
        // non-pending status (prevents status / agent spoofing on update).
        if (auth()->user()->isAgent()) {
            $data['agent_id'] = auth()->user()->agent_id;
        }
        if (! auth()->user()->hasPermission('mark_commission_paid')) {
            $data['status'] = 'pending';
        }

        $split = CommissionCalculator::split((float) ($data['amount'] ?? 0));
        $data['agency_amount'] = $split['agency_amount'];
        $data['agent_amount'] = $split['agent_amount'];
        $data['amount'] = $split['agent_amount']; // amount column stores the agent payout
        $data['source'] = Deal::find($data['deal_id'])?->type ?? 'sale';

        $commission->update($data);
        toastr()->success('Commission updated successfully.');

        return redirect()->route('commissions.index');
    }

    public function destroy(Commission $commission)
    {
        $this->authorizeAgentAccess($commission);
        $commission->delete();
        toastr()->success('Commission deleted successfully.');

        return redirect()->route('commissions.index');
    }

    public function markPaid(Commission $commission)
    {
        $this->authorizeAgentAccess($commission);

        if (! auth()->user()->hasPermission('mark_commission_paid')) {
            abort(403, 'You do not have permission to mark commissions as paid.');
        }

        $commission->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
        toastr()->success('Commission marked as paid.');

        return redirect()->back();
    }

    /**
     * AJAX preview: returns split for a property + deal type + amount.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|string|in:sale,buy,rent,installment',
            'amount' => 'required|numeric|min:0',
        ]);

        $split = CommissionCalculator::split((float) $request->amount);

        return response()->json($split);
    }
}
