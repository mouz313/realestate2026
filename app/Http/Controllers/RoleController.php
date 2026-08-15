<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $companyId = current_company_id();

        // Auto-create company-specific staff/client roles if they don't exist
        $this->ensureCompanyRolesExist($companyId);

        $roles = Role::where(function ($q) use ($companyId) {
            $q->whereNull('company_id')->orWhere('company_id', $companyId);
        })->with('permissions')->orderBy('is_system', 'desc')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    protected function ensureCompanyRolesExist(int $companyId): void
    {
        $permissionIds = Permission::whereNull('company_id')->pluck('id')->all();

        $descriptions = [
            'staff' => 'Regular team member',
            'client' => 'External client with limited access',
        ];

        foreach (['staff', 'client'] as $slug) {
            if (! Role::where('slug', $slug)->where('company_id', $companyId)->exists()) {
                $role = Role::create([
                    'company_id' => $companyId,
                    'name' => ucfirst($slug),
                    'slug' => $slug,
                    'description' => $descriptions[$slug] ?? '',
                    'is_system' => false,
                ]);

                if ($slug === 'staff') {
                    $role->permissions()->sync($permissionIds);
                }
            }
        }
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

        Role::create([
            'company_id' => current_company_id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_system' => false,
        ]);

        return redirect()->route('roles.index')
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

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorizeRoleOwnership($role);

        // Only block deletion of system roles (e.g., owner)
        if ($role->is_system && $role->slug === 'owner') {
            return back()->with('error', 'Cannot delete the system owner role.');
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return redirect()->route('roles.index')
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
        // Only the owner role is truly immutable
        if ($role->is_system && $role->slug === 'owner') {
            abort(403, 'Cannot modify the system owner role.');
        }

        $companyId = current_company_id();
        if ($role->company_id && $role->company_id !== $companyId) {
            abort(403, 'You can only manage roles for your company.');
        }
    }
}
