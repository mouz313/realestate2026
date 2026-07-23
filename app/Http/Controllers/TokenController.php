<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Token;
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

    public function store(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'received_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Token::create($request->all());
        toastr()->success('Token added successfully.');

        return redirect()->route('tokens.index');
    }

    public function show(Token $token)
    {
        $this->authorizeViaDeal($token);
        $token->load('deal');

        return view('tokens.show', compact('token'));
    }

    public function edit(Token $token)
    {
        $this->authorizeViaDeal($token);
        $deals = Deal::orderBy('deal_number')->get();

        return view('tokens.edit', compact('token', 'deals'));
    }

    public function update(Request $request, Token $token)
    {
        $this->authorizeViaDeal($token);
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'received_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $token->update($request->all());
        toastr()->success('Token updated successfully.');

        return redirect()->route('tokens.index');
    }

    public function destroy(Token $token)
    {
        $this->authorizeViaDeal($token);
        $token->delete();
        toastr()->success('Token deleted successfully.');

        return redirect()->route('tokens.index');
    }
}
