<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $groups = Permission::where(function ($q) {
            $q->whereNull('company_id')->orWhere('company_id', current_company_id());
        })->orderBy('group')->orderBy('name')->get()
            ->groupBy('group');

        return view('admin.permissions.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
        ]);

        Permission::create([
            'company_id' => current_company_id(),
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'group' => $request->group,
            'is_system' => false,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        $this->authorizePermissionOwnership($permission);

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorizePermissionOwnership($permission);

        $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'group' => $request->group,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $this->authorizePermissionOwnership($permission);

        if ($permission->is_system) {
            return back()->with('error', 'Cannot delete a system permission.');
        }

        $permission->roles()->detach();
        $permission->users()->detach();
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    protected function authorizePermissionOwnership(Permission $permission): void
    {
        if ($permission->is_system) {
            abort(403, 'Cannot modify a system permission.');
        }

        $companyId = current_company_id();
        if ($permission->company_id && $permission->company_id !== $companyId) {
            abort(403, 'You can only manage permissions for your company.');
        }
    }
}
