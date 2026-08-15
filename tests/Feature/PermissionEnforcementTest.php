<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PermissionEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->company = Company::create([
            'name' => 'Test Agency',
            'slug' => 'test-agency',
            'email' => 'agency@test.com',
            'is_active' => true,
        ]);
    }

    protected function makeUserForRole(string $roleSlug, string $email): User
    {
        $role = Role::where('slug', $roleSlug)
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $this->company->id))
            ->orderByRaw('company_id IS NULL')
            ->first();

        $user = User::create([
            'company_id' => $this->company->id,
            'name' => 'User '.ucfirst($roleSlug),
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $roleSlug,
            'is_active' => true,
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_staff_without_commission_permission_is_forbidden(): void
    {
        // The seeded staff role deliberately excludes view_all_commissions / view_own_commissions
        $staff = $this->makeUserForRole('staff', 'staff@test.com');
        $this->actingAs($staff);

        $this->get(route('commissions.index'))->assertForbidden();
    }

    public function test_owner_with_permission_can_view_commissions(): void
    {
        // The seeded owner role has all permissions, including view_all_commissions
        $owner = $this->makeUserForRole('owner', 'owner@test.com');
        $this->actingAs($owner);

        $this->get(route('commissions.index'))->assertStatus(200);
    }

    public function test_agent_can_reach_commissions_index(): void
    {
        // The seeded agent role has view_own_commissions, so the OR-middleware allows access
        $agent = $this->makeUserForRole('agent', 'agent-comm@test.com');
        $this->actingAs($agent);

        $this->get(route('commissions.index'))->assertStatus(200);
    }
}