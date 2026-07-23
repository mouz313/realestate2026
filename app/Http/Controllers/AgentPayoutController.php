<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentPayout;
use App\Models\Commission;
use Illuminate\Http\Request;

class AgentPayoutController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $agentPayouts = AgentPayout::with('agent')
            ->when($agentId, fn($q) => $q->where('agent_id', $agentId))
            ->latest()->paginate(15);
        return view('agent_payouts.index', compact('agentPayouts'));
    }

    public function create()
    {
        $agents = Agent::orderBy('name')->get();
        return view('agent_payouts.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'amount' => 'required|numeric|min:0',
            'payout_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'commission_ids' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();
        $data['commission_ids'] = $request->has('commission_ids') ? json_encode($request->commission_ids) : null;

        AgentPayout::create($data);
        toastr()->success('Agent payout added successfully.');
        return redirect()->route('agent-payouts.index');
    }

    public function show(AgentPayout $agentPayout)
    {
        $agentPayout->load('agent');
        return view('agent_payouts.show', compact('agentPayout'));
    }

    public function destroy(AgentPayout $agentPayout)
    {
        $agentPayout->delete();
        toastr()->success('Agent payout deleted successfully.');
        return redirect()->route('agent-payouts.index');
    }
}
