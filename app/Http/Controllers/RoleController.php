<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where(function ($q) {
            $q->whereNull('company_id')->orWhere('company_id', current_company_id());
        })->with('permissions')->orderBy('is_system', 'desc')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::create([
            'company_id' => current_company_id(),
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'is_system' => false,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorizeRoleOwnership($role);

        return view('admin.roles.edit', compact('role'));
    }

    public function assignPermissionsForm(Role $role)
    {
        $this->authorizeRoleOwnership($role);

        $permissions = Permission::where(function ($q) {
            $q->whereNull('company_id')->orWhere('company_id', current_company_id());
        })->orderBy('group')->orderBy('name')->get()
            ->groupBy('group');

        $rolePermissionIds = $role->permissions->pluck('id');

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeRoleOwnership($role);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorizeRoleOwnership($role);

        if ($role->is_system) {
            return back()->with('error', 'Cannot delete a system role.');
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $this->authorizeRoleOwnership($role);

        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);

        return back()->with('success', 'Permissions assigned to role.');
    }

    protected function authorizeRoleOwnership(Role $role): void
    {
        if ($role->is_system) {
            abort(403, 'Cannot modify a system role.');
        }

        $companyId = current_company_id();
        if ($role->company_id && $role->company_id !== $companyId) {
            abort(403, 'You can only manage roles for your company.');
        }
    }
}
