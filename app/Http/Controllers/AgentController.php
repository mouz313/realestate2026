<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
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
            'create_login' => 'nullable|boolean',
            'login_email' => 'nullable|email|max:255|required_if:create_login,1|unique:users,email',
            'login_password' => 'nullable|string|min:8|required_if:create_login,1',
        ]);

        $data = $request->except(['photo', 'create_login', 'login_email', 'login_password']);
        $data['commission_rate'] = $request->filled('commission_rate') ? $request->commission_rate : 2.50;
        $data['type'] = $request->filled('type') ? $request->type : 'in_house';
        $data['specializations'] = $request->filled('specializations') ? $request->specializations : null;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('agents', 'public');
        }

        $agent = Agent::create($data);

        if ($request->boolean('create_login')) {
            $user = $this->createAgentLogin($agent, $request->login_email, $request->login_password);
            $agent->user_id = $user->id;
            $agent->save();
        }

        toastr()->success('Agent added successfully.');

        return redirect()->route('team.index', ['type' => 'agents']);
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
            'create_login' => 'nullable|boolean',
            'login_email' => 'nullable|email|max:255|unique:users,email,'.($agent->user_id ?? 'NULL').',id',
            'login_password' => 'nullable|string|min:8',
        ]);

        $data = $request->except(['photo', 'create_login', 'login_email', 'login_password']);
        $data['commission_rate'] = $request->filled('commission_rate') ? $request->commission_rate : 2.50;
        $data['type'] = $request->filled('type') ? $request->type : 'in_house';
        $data['specializations'] = $request->filled('specializations') ? $request->specializations : null;

        if ($request->hasFile('photo')) {
            if ($agent->photo) {
                Storage::disk('public')->delete($agent->photo);
            }
            $data['photo'] = $request->file('photo')->store('agents', 'public');
        }

        $agent->update($data);

        if ($agent->user) {
            $user = $agent->user;
            $changed = false;

            if ($request->filled('login_email') && $request->login_email !== $user->email) {
                $user->email = $request->login_email;
                $changed = true;
            }

            if ($request->filled('login_password')) {
                $user->password = $request->login_password;
                $changed = true;
            }

            if ($changed) {
                $user->save();
            }
        } elseif ($request->boolean('create_login')) {
            $user = $this->createAgentLogin($agent, $request->login_email, $request->login_password);
            $agent->user_id = $user->id;
            $agent->save();
        }

        toastr()->success('Agent updated successfully.');

        return redirect()->route('team.index', ['type' => 'agents']);
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        toastr()->success('Agent deleted successfully.');

        return redirect()->route('team.index', ['type' => 'agents']);
    }

    protected function createAgentLogin(Agent $agent, string $email, string $password): User
    {
        $user = User::create([
            'company_id' => $agent->company_id,
            'name' => $agent->name,
            'email' => $email,
            'password' => $password,
            'role' => 'agent',
            'is_active' => true,
            'email_verified_at' => now(),
            'agent_id' => $agent->id,
        ]);

        $user->assignRole('agent');

        return $user;
    }
}
