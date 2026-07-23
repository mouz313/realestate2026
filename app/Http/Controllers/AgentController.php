<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(fn ($req, $next) => Gate::authorize('admin') ? $next($req) : abort(403));
    }

    public function index()
    {
        $agents = Agent::latest()->paginate(15);
        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        return view('agents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'cnic' => 'nullable|string|max:50',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bio' => 'nullable|string|max:5000',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'languages' => 'nullable|string|max:500',
            'facebook' => 'nullable|string|max:500',
            'twitter' => 'nullable|string|max:500',
            'linkedin' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:500',
            'website' => 'nullable|string|max:500',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
        ]);

        $data = $request->except('photo');
        $data['commission_rate'] = $request->filled('commission_rate') ? $request->commission_rate : 2.50;
        $data['type'] = $request->filled('type') ? $request->type : 'in_house';
        $data['specializations'] = $request->filled('specializations') ? json_encode($request->specializations) : null;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('agents', 'public');
        }

        Agent::create($data);
        toastr()->success('Agent added successfully.');
        return redirect()->route('agents.index');
    }

    public function show(Agent $agent)
    {
        $agent->load(['deals' => fn ($q) => $q->latest()->limit(10), 'commissions' => fn ($c) => $c->latest()->limit(10)]);
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'cnic' => 'nullable|string|max:50',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bio' => 'nullable|string|max:5000',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'languages' => 'nullable|string|max:500',
            'facebook' => 'nullable|string|max:500',
            'twitter' => 'nullable|string|max:500',
            'linkedin' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:500',
            'website' => 'nullable|string|max:500',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
        ]);

        $data = $request->except('photo');
        $data['commission_rate'] = $request->filled('commission_rate') ? $request->commission_rate : 2.50;
        $data['type'] = $request->filled('type') ? $request->type : 'in_house';
        $data['specializations'] = $request->filled('specializations') ? json_encode($request->specializations) : null;

        if ($request->hasFile('photo')) {
            if ($agent->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($agent->photo);
            }
            $data['photo'] = $request->file('photo')->store('agents', 'public');
        }

        $agent->update($data);
        toastr()->success('Agent updated successfully.');
        return redirect()->route('agents.index');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        toastr()->success('Agent deleted successfully.');
        return redirect()->route('agents.index');
    }
}
