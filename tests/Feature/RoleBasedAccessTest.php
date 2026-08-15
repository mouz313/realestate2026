<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
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

        session(['company_id' => $this->company->id]);
    }

    protected function makeUser(string $roleSlug, ?Agent $agent = null): User
    {
        $role = Role::where('slug', $roleSlug)
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $this->company->id))
            ->orderByRaw('company_id IS NULL')
            ->first();

        $this->assertNotNull($role, "Role '{$roleSlug}' was not seeded.");

        $agentId = null;
        if ($agent) {
            $agentId = $agent->id;
        }

        $user = User::create([
            'company_id' => $this->company->id,
            'name' => 'Test '.ucfirst($roleSlug),
            'email' => strtolower($roleSlug).'@test.com',
            'password' => Hash::make('password'),
            'role' => $roleSlug,
            'agent_id' => $agentId,
            'is_active' => true,
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_owner_can_access_roles_management(): void
    {
        $owner = $this->makeUser('owner');
        $this->actingAs($owner);

        $this->get(route('roles.index'))->assertStatus(200);
        $this->get(route('permissions.index'))->assertStatus(200);
    }

    public function test_staff_cannot_manage_roles(): void
    {
        $staff = $this->makeUser('staff');
        $this->actingAs($staff);

        $this->get(route('roles.index'))->assertForbidden();
    }

    public function test_agent_cannot_manage_roles(): void
    {
        $agent = Agent::create([
            'company_id' => $this->company->id,
            'name' => 'Sales Agent',
            'phone' => '03000000001',
            'cnic' => 'SALES-CNIC-1',
            'status' => 'active',
        ]);
        $agentUser = $this->makeUser('agent', $agent);
        $this->actingAs($agentUser);

        $this->get(route('roles.index'))->assertForbidden();
    }

    public function test_agent_can_access_properties_index(): void
    {
        $agent = Agent::create([
            'company_id' => $this->company->id,
            'name' => 'Sales Agent',
            'phone' => '03000000002',
            'cnic' => 'SALES-CNIC-2',
            'status' => 'active',
        ]);
        $agentUser = $this->makeUser('agent', $agent);
        $this->actingAs($agentUser);

        $this->get(route('properties.index'))->assertStatus(200);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}