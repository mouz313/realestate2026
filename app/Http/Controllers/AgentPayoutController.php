<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentPayout;
use App\Models\Commission;
use App\Http\Requests\AgentPayoutRequest;
use App\Notifications\CommissionPayoutMade;
use Illuminate\Http\Request;

class AgentPayoutController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $agentPayouts = AgentPayout::with('agent')
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->latest()->paginate(15);

        return view('agent_payouts.index', compact('agentPayouts'));
    }

    public function create()
    {
        $agents = Agent::orderBy('name')->get();

        return view('agent_payouts.create', compact('agents'));
    }

    public function store(AgentPayoutRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = current_company_id();

        // Per-row authorization: an agent may only create a payout for
        // themselves, and referenced commissions must belong to that agent.
        if (auth()->user()->isAgent()) {
            $data['agent_id'] = auth()->user()->agent_id;
        }

        if ($request->has('commission_ids')) {
            $ids = $request->commission_ids;
            if (auth()->user()->isAgent()) {
                $allowed = Commission::where('agent_id', $data['agent_id'])
                    ->where('company_id', $data['company_id'])
                    ->pluck('id')->all();
                $ids = collect($ids)->intersect($allowed)->values()->all();
            }
            $data['commission_ids'] = json_encode($ids);
        } else {
            $data['commission_ids'] = null;
        }

        $payout = AgentPayout::create($data);

        $payout->load('agent');
        $recipients = [];
        if ($payout->agent && $payout->agent->user) {
            $recipients[] = $payout->agent->user;
        }
        notify_company($payout->company_id, CommissionPayoutMade::class, [$payout], $recipients);

        toastr()->success('Agent payout added successfully.');

        return redirect()->route('agent-payouts.index');
    }

    public function show(AgentPayout $agentPayout)
    {
        $this->authorize('update', $agentPayout);
        $agentPayout->load('agent');

        return view('agent_payouts.show', compact('agentPayout'));
    }

    public function edit(AgentPayout $agentPayout)
    {
        $this->authorize('update', $agentPayout);
        $agents = Agent::orderBy('name')->get();

        return view('agent_payouts.edit', compact('agentPayout', 'agents'));
    }

    public function update(AgentPayoutRequest $request, AgentPayout $agentPayout)
    {
        $this->authorize('update', $agentPayout);
        $data = $request->validated();

        // Mirror store() authorization: an agent may only update their own
        // payout, and referenced commissions must belong to that agent.
        if (auth()->user()->isAgent()) {
            $data['agent_id'] = auth()->user()->agent_id;
        }

        if ($request->has('commission_ids')) {
            $ids = $request->commission_ids;
            if (auth()->user()->isAgent()) {
                $allowed = Commission::where('agent_id', $data['agent_id'])
                    ->where('company_id', $data['company_id'] ?? current_company_id())
                    ->pluck('id')->all();
                $ids = collect($ids)->intersect($allowed)->values()->all();
            }
            $data['commission_ids'] = json_encode($ids);
        } else {
            $data['commission_ids'] = null;
        }

        $agentPayout->update($data);
        toastr()->success('Agent payout updated successfully.');

        return redirect()->route('agent-payouts.index');
    }

    public function destroy(AgentPayout $agentPayout)
    {
        $this->authorize('update', $agentPayout);
        $agentPayout->delete();
        toastr()->success('Agent payout deleted successfully.');

        return redirect()->route('agent-payouts.index');
    }
}
