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

        $companyId = current_company_id();
        $slug = $this->uniqueSlug($request->name, $companyId);

        Role::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'is_system' => false,
        ]);

        toastr()->success('Role created successfully.');

        return redirect()->route('roles.index');
    }

    protected function uniqueSlug(string $name, ?int $companyId): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Role::where('slug', $slug)
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $companyId))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function edit(Role $role)
    {
        $this->authorizeRoleSettings($role);

        return view('admin.roles.edit', compact('role'));
    }

    public function assignPermissionsForm(Role $role)
    {
        $this->authorizeRolePermissions($role);

        $permissions = Permission::where(function ($q) {
            $q->whereNull('company_id')->orWhere('company_id', current_company_id());
        })->orderBy('group')->orderBy('name')->get()
            ->groupBy('group');

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeRoleSettings($role);

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

        toastr()->success('Role updated successfully.');

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $this->authorizeRoleSettings($role);

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        toastr()->success('Role deleted successfully.');

        return redirect()->route('roles.index');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $this->authorizeRolePermissions($role);

        $permissionIds = $request->input('permissions', []);

        // Only attach permissions that belong to the current company or are
        // global, preventing cross-tenant permission assignment (IDOR).
        $allowedIds = Permission::forCompany(current_company_id())
            ->whereIn('id', $permissionIds)
            ->pluck('id')
            ->all();

        $role->permissions()->sync($allowedIds);

        toastr()->success('Permissions assigned to role.');

        return back();
    }

    protected function authorizeRoleSettings(Role $role): void
    {
        // System role settings (name, active, deletion) are immutable.
        // Only permission assignment is allowed via authorizeRolePermissions.
        if ($role->is_system) {
            abort(403, 'Cannot modify settings of a system role.');
        }

        $companyId = current_company_id();
        if ($role->company_id && $role->company_id !== $companyId) {
            abort(403, 'You can only manage roles for your company.');
        }
    }

    protected function authorizeRolePermissions(Role $role): void
    {
        // Permission assignment is allowed for any role (including the system
        // owner role), as long as it belongs to the current company.
        $companyId = current_company_id();
        if ($role->company_id && $role->company_id !== $companyId) {
            abort(403, 'You can only manage roles for your company.');
        }
    }
}
