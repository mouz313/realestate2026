<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Token;
use App\Http\Requests\TokenRequest;
use App\Notifications\TokenReceived;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $tokens = Token::with('deal')
            ->when($agentId, fn ($q) => $q->whereHas('deal', fn ($dq) => $dq->where('agent_id', $agentId)))
            ->latest()->paginate(15);

        return view('tokens.index', compact('tokens'));
    }

    public function create()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->orderBy('deal_number')->get();

        return view('tokens.create', compact('deals'));
    }

    public function store(TokenRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = current_company_id();

        // Per-row authorization: only admins may mark a token as received;
        // everyone else's tokens start pending (prevents status spoofing).
        if (! auth()->user()->isAdmin()) {
            $data['status'] = 'pending';
        }

        $token = Token::create($data);

        $recipients = [];
        if ($token->deal && $token->deal->agent && $token->deal->agent->user) {
            $recipients[] = $token->deal->agent->user;
        }
        notify_company($token->company_id ?? current_company_id(), TokenReceived::class, [$token], $recipients);

        toastr()->success('Token added successfully.');

        return redirect()->route('tokens.index');
    }

    public function show(Token $token)
    {
        $this->authorize('update', $token);
        $token->load('deal');

        return view('tokens.show', compact('token'));
    }

    public function edit(Token $token)
    {
        $this->authorize('update', $token);
        $deals = Deal::orderBy('deal_number')->get();

        return view('tokens.edit', compact('token', 'deals'));
    }

    public function update(TokenRequest $request, Token $token)
    {
        $data = $request->validated();

        // Only admins may change a token's status (mirrors store() guard).
        if (! auth()->user()->isAdmin()) {
            $data['status'] = 'pending';
        }

        $token->update($data);
        toastr()->success('Token updated successfully.');

        return redirect()->route('tokens.index');
    }

    public function destroy(Token $token)
    {
        $this->authorize('update', $token);
        $token->delete();
        toastr()->success('Token deleted successfully.');

        return redirect()->route('tokens.index');
    }
}
