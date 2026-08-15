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

class DashboardAccessTest extends TestCase
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

        $user = User::create([
            'company_id' => $this->company->id,
            'name' => 'User '.ucfirst($roleSlug),
            'email' => strtolower($roleSlug).'-dash@test.com',
            'password' => Hash::make('password'),
            'role' => $roleSlug,
            'agent_id' => $agent?->id,
            'is_active' => true,
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    protected function createAgent(User $linkedUser, string $name = 'Dash Agent'): Agent
    {
        $agent = Agent::create([
            'company_id' => $this->company->id,
            'user_id' => $linkedUser->id,
            'name' => $name,
            'phone' => '03001230000',
            'cnic' => '42101-7777777-7',
            'status' => 'active',
        ]);

        $linkedUser->update(['agent_id' => $agent->id]);

        return $agent;
    }

    public function test_owner_can_access_dashboard(): void
    {
        $owner = $this->makeUser('owner');

        $this->actingAs($owner)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_staff_can_access_dashboard(): void
    {
        $staff = $this->makeUser('staff');

        $this->actingAs($staff)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_agent_can_access_dashboard(): void
    {
        $agentUser = $this->makeUser('agent');
        $this->createAgent($agentUser);

        $this->actingAs($agentUser)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_dashboard_renders_for_guest_redirects_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
