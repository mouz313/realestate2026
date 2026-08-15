<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;

class CompanyObserver
{
    public function created(Company $company): void
    {
        $this->ensureCompanyRoles($company->id);
    }

    public function restored(Company $company): void
    {
        $this->ensureCompanyRoles($company->id);
    }

    public function ensureCompanyRoles(int $companyId): void
    {
        $allPermissions = Permission::whereNull('company_id')->pluck('id')->all();
        $staffPermissions = $this->buildStaffPermissions();
        $agentPermissions = $this->buildAgentPermissions();

        $this->createRole($companyId, 'admin', 'Admin', 'Administrator with full operational access', $allPermissions);
        $this->createRole($companyId, 'staff', 'Staff', 'Back-office staff', $staffPermissions);
        $this->createRole($companyId, 'agent', 'Agent', 'Sales agent', $agentPermissions);
    }

    protected function createRole(int $companyId, string $slug, string $name, string $description, array $permissionIds): void
    {
        $role = Role::firstOrCreate(
            ['slug' => $slug, 'company_id' => $companyId],
            ['name' => $name, 'description' => $description, 'is_system' => true, 'is_active' => true]
        );

        if (! $role->wasRecentlyCreated && $role->permissions()->count() === 0) {
            $role->permissions()->sync($permissionIds);
        } elseif ($role->wasRecentlyCreated) {
            $role->permissions()->sync($permissionIds);
        }
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
            'view_rent_agreements',
            'view_installments',
            'view_own_commissions',
            'view_reports',
            'view_settings',
        ];

        return Permission::whereNull('company_id')
            ->whereIn('slug', $allowed)
            ->pluck('id')
            ->all();
    }
}
