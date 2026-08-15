<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $companyId = current_company_id();

        $roles = Role::where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
            ->orderByRaw('is_system DESC, name')
            ->get();

        $agents = Agent::whereNull('user_id')->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'agents'));
    }

    public function store(Request $request)
    {
        $companyId = current_company_id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'is_active' => 'nullable|boolean',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'agent_id' => 'nullable|exists:agents,id',
        ]);

        $roleIds = collect($request->input('roles', []))
            ->filter(function ($id) use ($companyId) {
                return Role::where('id', $id)
                    ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
                    ->exists();
            })
            ->values()
            ->all();

        if (empty($roleIds)) {
            return back()->withErrors(['roles' => 'Select at least one valid role for this company.'])->withInput();
        }

        $agent = null;
        if ($request->filled('agent_id')) {
            $agent = Agent::where('company_id', $companyId)->whereNull('user_id')->find($request->agent_id);
        }

        $user = User::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'staff',
            'is_active' => $request->boolean('is_active'),
            'email_verified_at' => now(),
            'agent_id' => $agent?->id,
        ]);

        $user->roles()->sync($roleIds);

        if ($agent) {
            $agent->user_id = $user->id;
            $agent->save();
        }

        $user->syncPrimaryRoleCache();

        toastr()->success('User created.');

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $companyId = current_company_id();

        $roles = Role::where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
            ->orderByRaw('is_system DESC, name')
            ->get();

        $permissions = Permission::where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
            ->orderBy('group')->orderBy('name')
            ->get()
            ->groupBy('group');

        $assignedRoleIds = $user->roles->pluck('id');

        $directPermissions = $user->permissions->mapWithKeys(fn ($p) => [$p->id => $p->pivot->granted]);

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'assignedRoleIds', 'directPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $companyId = current_company_id();

        $roleIds = collect($request->input('roles', []))
            ->filter(function ($id) use ($companyId) {
                return Role::where('id', $id)
                    ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
                    ->exists();
            })
            ->values()
            ->all();

        $user->roles()->sync($roleIds);

        $granted = $request->input('permissions_granted', []);
        $denied = $request->input('permissions_denied', []);

        $user->permissions()->detach();

        foreach ($granted as $id) {
            $user->permissions()->syncWithoutDetaching([$id => ['granted' => true]]);
        }

        foreach ($denied as $id) {
            $user->permissions()->syncWithoutDetaching([$id => ['granted' => false]]);
        }

        $user->syncPrimaryRoleCache();

        toastr()->success('User roles and permissions updated.');

        return redirect()->route('users.index');
    }
}
