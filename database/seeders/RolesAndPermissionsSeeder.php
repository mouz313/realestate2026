<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPermissions();
        $this->seedSystemRoles();
        $this->seedCompanyRolesForAllCompanies();
    }

    protected function seedPermissions(): void
    {
        $catalog = [
            'Dashboard' => [
                ['slug' => 'view_dashboard', 'name' => 'View Dashboard'],
                ['slug' => 'view_company_dashboard', 'name' => 'View Company Dashboard'],
            ],
            'Clients' => [
                ['slug' => 'view_clients', 'name' => 'View Clients'],
                ['slug' => 'create_clients', 'name' => 'Create Clients'],
                ['slug' => 'edit_clients', 'name' => 'Edit Clients'],
                ['slug' => 'delete_clients', 'name' => 'Delete Clients'],
            ],
            'Properties' => [
                ['slug' => 'view_properties', 'name' => 'View All Properties'],
                ['slug' => 'view_own_properties', 'name' => 'View Own Properties'],
                ['slug' => 'create_properties', 'name' => 'Create Properties'],
                ['slug' => 'edit_own_properties', 'name' => 'Edit Own Properties'],
                ['slug' => 'edit_any_properties', 'name' => 'Edit Any Properties'],
                ['slug' => 'delete_properties', 'name' => 'Delete Properties'],
                ['slug' => 'manage_property_media', 'name' => 'Manage Property Media'],
            ],
            'Deals' => [
                ['slug' => 'view_deals', 'name' => 'View All Deals'],
                ['slug' => 'view_own_deals', 'name' => 'View Own Deals'],
                ['slug' => 'create_deals', 'name' => 'Create Deals'],
                ['slug' => 'edit_own_deals', 'name' => 'Edit Own Deals'],
                ['slug' => 'edit_any_deals', 'name' => 'Edit Any Deals'],
                ['slug' => 'close_deals', 'name' => 'Close Deals'],
                ['slug' => 'delete_deals', 'name' => 'Delete Deals'],
                ['slug' => 'manage_tokens', 'name' => 'Manage Tokens'],
            ],
            'Quotations' => [
                ['slug' => 'view_quotations', 'name' => 'View Quotations'],
                ['slug' => 'create_quotations', 'name' => 'Create Quotations'],
                ['slug' => 'edit_quotations', 'name' => 'Edit Quotations'],
                ['slug' => 'send_quotations', 'name' => 'Send Quotations'],
                ['slug' => 'delete_quotations', 'name' => 'Delete Quotations'],
            ],
            'Invoices' => [
                ['slug' => 'view_invoices', 'name' => 'View Invoices'],
                ['slug' => 'create_invoices', 'name' => 'Create Invoices'],
                ['slug' => 'edit_invoices', 'name' => 'Edit Invoices'],
                ['slug' => 'delete_invoices', 'name' => 'Delete Invoices'],
            ],
            'Payments' => [
                ['slug' => 'view_all_payments', 'name' => 'View All Payments'],
                ['slug' => 'record_payments', 'name' => 'Record Payments'],
                ['slug' => 'edit_payments', 'name' => 'Edit Payments'],
                ['slug' => 'delete_payments', 'name' => 'Delete Payments'],
            ],
            'Visits' => [
                ['slug' => 'view_visits', 'name' => 'View Visits'],
                ['slug' => 'create_visits', 'name' => 'Create Visits'],
                ['slug' => 'edit_visits', 'name' => 'Edit Visits'],
                ['slug' => 'delete_visits', 'name' => 'Delete Visits'],
            ],
            'Commissions & Payouts' => [
                ['slug' => 'view_own_commissions', 'name' => 'View Own Commissions'],
                ['slug' => 'view_all_commissions', 'name' => 'View All Commissions'],
                ['slug' => 'manage_commissions', 'name' => 'Manage Commissions'],
                ['slug' => 'mark_commission_paid', 'name' => 'Mark Commission Paid'],
                ['slug' => 'view_payouts', 'name' => 'View Payouts'],
                ['slug' => 'create_payouts', 'name' => 'Create Payouts'],
                ['slug' => 'approve_payouts', 'name' => 'Approve Payouts'],
            ],
            'Team' => [
                ['slug' => 'view_agents', 'name' => 'View Agents'],
                ['slug' => 'manage_agents', 'name' => 'Manage Agents'],
                ['slug' => 'view_staff', 'name' => 'View Staff'],
                ['slug' => 'manage_staff', 'name' => 'Manage Staff'],
            ],
            'RBAC' => [
                ['slug' => 'view_roles', 'name' => 'View Roles'],
                ['slug' => 'manage_roles', 'name' => 'Manage Roles'],
                ['slug' => 'view_permissions', 'name' => 'View Permissions'],
                ['slug' => 'manage_permissions', 'name' => 'Manage Permissions'],
                ['slug' => 'assign_user_roles', 'name' => 'Assign User Roles'],
            ],
            'Settings' => [
                ['slug' => 'view_settings', 'name' => 'View Settings'],
                ['slug' => 'edit_settings', 'name' => 'Edit Settings'],
                ['slug' => 'manage_cities', 'name' => 'Manage Cities'],
                ['slug' => 'manage_item_templates', 'name' => 'Manage Item Templates'],
            ],
            'Reports' => [
                ['slug' => 'view_reports', 'name' => 'View Reports'],
                ['slug' => 'export_reports', 'name' => 'Export Reports'],
            ],
            'Activity' => [
                ['slug' => 'view_activity_log', 'name' => 'View Activity Log'],
                ['slug' => 'view_expenses', 'name' => 'View Expenses'],
                ['slug' => 'manage_expenses', 'name' => 'Manage Expenses'],
            ],
        ];

        foreach ($catalog as $group => $permissions) {
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(
                    ['slug' => $perm['slug'], 'company_id' => null],
                    [
                        'name' => $perm['name'],
                        'description' => $perm['name'],
                        'group' => $group,
                        'is_system' => true,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    protected function seedSystemRoles(): void
    {
        $allPermissions = Permission::whereNull('company_id')->pluck('id')->all();

        $owner = Role::firstOrCreate(
            ['slug' => 'owner', 'company_id' => null],
            ['name' => 'Owner', 'description' => 'Company owner with full access', 'is_system' => true, 'is_active' => true]
        );
        $owner->permissions()->sync($allPermissions);
    }

    protected function seedCompanyRolesForAllCompanies(): void
    {
        $companies = Company::withTrashed()->get();

        if ($companies->isEmpty()) {
            return;
        }

        $allPermissions = Permission::whereNull('company_id')->pluck('id')->all();
        $staffPermissions = $this->buildStaffPermissions();
        $agentPermissions = $this->buildAgentPermissions();

        foreach ($companies as $company) {
            $this->createCompanyRole($company->id, 'admin', 'Admin', 'Administrator with full operational access', $allPermissions);
            $this->createCompanyRole($company->id, 'staff', 'Staff', 'Back-office staff', $staffPermissions);
            $this->createCompanyRole($company->id, 'agent', 'Agent', 'Sales agent', $agentPermissions);
        }
    }

    protected function createCompanyRole(int $companyId, string $slug, string $name, string $description, array $permissionIds): void
    {
        $role = Role::firstOrCreate(
            ['slug' => $slug, 'company_id' => $companyId],
            ['name' => $name, 'description' => $description, 'is_system' => true, 'is_active' => true]
        );

        $role->permissions()->sync($permissionIds);
    }

    protected function buildStaffPermissions(): array
    {
        $excluded = [
            'view_own_commissions',
            'view_all_commissions',
            'manage_commissions',
            'mark_commission_paid',
            'view_payouts',
            'create_payouts',
            'approve_payouts',
            'manage_roles',
            'manage_permissions',
            'assign_user_roles',
        ];

        return Permission::whereNull('company_id')
            ->whereNotIn('slug', $excluded)
            ->pluck('id')
            ->all();
    }

    protected function buildAgentPermissions(): array
    {
        $allowed = [
            'view_dashboard',
            'view_own_properties',
            'create_properties',
            'edit_own_properties',
            'manage_property_media',
            'view_own_deals',
            'create_deals',
            'edit_own_deals',
            'close_deals',
            'view_quotations',
            'create_quotations',
            'edit_quotations',
            'send_quotations',
            'view_clients',
            'create_clients',
            'edit_clients',
            'view_visits',
            'create_visits',
            'edit_visits',
            'view_own_commissions',
            'view_reports',
            'view_settings',
            'view_dashboard',
            'view_payments',
            'view_invoices',
        ];

        return Permission::whereNull('company_id')
            ->whereIn('slug', $allowed)
            ->pluck('id')
            ->all();
    }
}
