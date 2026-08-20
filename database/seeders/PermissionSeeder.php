<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $systemPermissions = [
            ['slug' => 'clients.view', 'name' => 'View Clients', 'group' => 'Clients'],
            ['slug' => 'clients.create', 'name' => 'Create Clients', 'group' => 'Clients'],
            ['slug' => 'clients.edit', 'name' => 'Edit Clients', 'group' => 'Clients'],
            ['slug' => 'clients.delete', 'name' => 'Delete Clients', 'group' => 'Clients'],

            ['slug' => 'properties.view', 'name' => 'View Properties', 'group' => 'Properties'],
            ['slug' => 'properties.create', 'name' => 'Create Properties', 'group' => 'Properties'],
            ['slug' => 'properties.edit', 'name' => 'Edit Properties', 'group' => 'Properties'],
            ['slug' => 'properties.delete', 'name' => 'Delete Properties', 'group' => 'Properties'],

            ['slug' => 'deals.view', 'name' => 'View Deals', 'group' => 'Deals'],
            ['slug' => 'deals.create', 'name' => 'Create Deals', 'group' => 'Deals'],
            ['slug' => 'deals.edit', 'name' => 'Edit Deals', 'group' => 'Deals'],
            ['slug' => 'deals.delete', 'name' => 'Delete Deals', 'group' => 'Deals'],

            ['slug' => 'quotations.view', 'name' => 'View Quotations', 'group' => 'Quotations'],
            ['slug' => 'quotations.create', 'name' => 'Create Quotations', 'group' => 'Quotations'],
            ['slug' => 'quotations.edit', 'name' => 'Edit Quotations', 'group' => 'Quotations'],
            ['slug' => 'quotations.delete', 'name' => 'Delete Quotations', 'group' => 'Quotations'],

            ['slug' => 'invoices.view', 'name' => 'View Invoices', 'group' => 'Invoices'],
            ['slug' => 'invoices.create', 'name' => 'Create Invoices', 'group' => 'Invoices'],
            ['slug' => 'invoices.edit', 'name' => 'Edit Invoices', 'group' => 'Invoices'],
            ['slug' => 'invoices.delete', 'name' => 'Delete Invoices', 'group' => 'Invoices'],

            ['slug' => 'payments.view', 'name' => 'View Payments', 'group' => 'Payments'],
            ['slug' => 'payments.edit', 'name' => 'Edit Payments', 'group' => 'Payments'],
            ['slug' => 'payments.delete', 'name' => 'Delete Payments', 'group' => 'Payments'],

            ['slug' => 'reports.view', 'name' => 'View Reports', 'group' => 'Reports'],
            ['slug' => 'settings.view', 'name' => 'View Settings', 'group' => 'Settings'],
            ['slug' => 'activity_log.view', 'name' => 'View Activity Log', 'group' => 'Settings'],

            ['slug' => 'agents.view', 'name' => 'View Team', 'group' => 'Team'],
            ['slug' => 'agents.create', 'name' => 'Create Team Members', 'group' => 'Team'],
            ['slug' => 'agents.edit', 'name' => 'Edit Team Members', 'group' => 'Team'],
            ['slug' => 'agents.delete', 'name' => 'Delete Team Members', 'group' => 'Team'],
        ];

        $allPermissions = collect();
        foreach ($systemPermissions as $perm) {
            $p = Permission::firstOrCreate(
                ['slug' => $perm['slug'], 'company_id' => null],
                ['name' => $perm['name'], 'description' => $perm['name'], 'group' => $perm['group'], 'is_system' => true]
            );
            $allPermissions->push($p);
        }

        $ownerPermissions = $allPermissions->pluck('id')->all();

        foreach (['owner'] as $slug) {
            $role = Role::firstOrCreate(
                ['slug' => $slug, 'company_id' => null],
                ['name' => ucfirst($slug), 'description' => 'Owner with full access', 'is_system' => true]
            );
            $role->permissions()->sync($ownerPermissions);
        }

        // Staff and client roles are created per-company on demand (see RoleController::index)
        // This allows company admins to customize permissions for their own staff/client roles
    }
}
