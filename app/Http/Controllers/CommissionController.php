<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Commission;
use App\Models\Deal;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $commissions = Commission::with(['deal', 'agent'])
            ->when($agentId, fn($q) => $q->where('agent_id', $agentId))
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

        Commission::create($request->all());
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
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'agent_id' => 'required|exists:agents,id',
            'type' => 'required|string|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $commission->update($request->all());
        toastr()->success('Commission updated successfully.');
        return redirect()->route('commissions.index');
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        toastr()->success('Commission deleted successfully.');
        return redirect()->route('commissions.index');
    }

    public function markPaid(Commission $commission)
    {
        $commission->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
        toastr()->success('Commission marked as paid.');
        return redirect()->back();
    }
}
