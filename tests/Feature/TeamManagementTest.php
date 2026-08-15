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

class TeamManagementTest extends TestCase
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

        $this->admin = $this->makeUser('admin', 'admin-team@test.com');
    }

    protected function makeUser(string $roleSlug, string $email): User
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

    protected function createAgent(string $name = 'Agent Ali', ?User $linkedUser = null): Agent
    {
        $agent = Agent::create([
            'company_id' => $this->company->id,
            'user_id' => $linkedUser?->id,
            'name' => $name,
            'phone' => '03001234567',
            'cnic' => '42101-'.mt_rand(1000000, 9999999).'-'.mt_rand(1, 9),
            'status' => 'active',
        ]);

        if ($linkedUser) {
            $linkedUser->update(['agent_id' => $agent->id]);
        }

        return $agent;
    }

    public function test_team_page_lists_agents_and_staff(): void
    {
        $agent = $this->createAgent();
        $this->makeUser('staff', 'staff-team@test.com');

        $this->actingAs($this->admin)
            ->get(route('team.index'))
            ->assertOk()
            ->assertSee($agent->name);

        $this->actingAs($this->admin)
            ->get(route('team.index', ['type' => 'staff']))
            ->assertOk()
            ->assertSee('staff-team@test.com');
    }

    public function test_staff_can_view_team_page(): void
    {
        $staff = $this->makeUser('staff', 'staff2-team@test.com');

        $this->actingAs($staff)
            ->get(route('team.index'))
            ->assertOk();
    }

    public function test_agent_cannot_view_team_page(): void
    {
        $agentUser = $this->makeUser('agent', 'agent-team@test.com');

        $this->actingAs($agentUser)
            ->get(route('team.index'))
            ->assertForbidden();
    }

    public function test_agent_create_without_login_creates_no_user(): void
    {
        $this->actingAs($this->admin)
            ->post(route('agents.store'), [
                'name' => 'Agent No Login',
                'phone' => '03013334444',
                'cnic' => '42101-1111111-1',
            ])
            ->assertRedirect(route('team.index', ['type' => 'agents']));

        $agent = Agent::where('name', 'Agent No Login')->first();

        $this->assertNotNull($agent);
        $this->assertNull($agent->user_id);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_agent_create_with_login_creates_linked_user(): void
    {
        $this->actingAs($this->admin)
            ->post(route('agents.store'), [
                'name' => 'Agent With Login',
                'phone' => '03013335555',
                'cnic' => '42101-2222222-2',
                'create_login' => '1',
                'login_email' => 'agent-login@test.com',
                'login_password' => 'secret123',
            ])
            ->assertRedirect(route('team.index', ['type' => 'agents']));

        $agent = Agent::where('name', 'Agent With Login')->first();
        $user = User::where('email', 'agent-login@test.com')->first();

        $this->assertNotNull($agent);
        $this->assertNotNull($user);
        $this->assertSame($user->id, $agent->user_id);
        $this->assertSame($agent->id, $user->agent_id);
        $this->assertTrue($user->hasRole('agent'));
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_agent_create_with_login_requires_credentials(): void
    {
        $this->actingAs($this->admin)
            ->post(route('agents.store'), [
                'name' => 'Bad Login Agent',
                'phone' => '03013336666',
                'cnic' => '42101-3333333-3',
                'create_login' => '1',
            ])
            ->assertSessionHasErrors(['login_email', 'login_password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_update_agent_resets_login_password_and_email(): void
    {
        $user = $this->makeUser('agent', 'agent-update@test.com');
        $agent = $this->createAgent('Agent Update', $user);

        $this->actingAs($this->admin)
            ->put(route('agents.update', $agent), [
                'name' => 'Agent Update',
                'phone' => '03013337777',
                'cnic' => '42101-4444444-4',
                'login_email' => 'agent-updated@test.com',
                'login_password' => 'newsecret123',
            ])
            ->assertRedirect(route('team.index', ['type' => 'agents']));

        $user->refresh();

        $this->assertSame('agent-updated@test.com', $user->email);
        $this->assertTrue(Hash::check('newsecret123', $user->password));
        $this->assertSame($agent->id, $user->agent_id);
    }

    public function test_update_agent_can_create_login_for_agent_without_user(): void
    {
        $agent = $this->createAgent('Agent No User Yet');

        $this->actingAs($this->admin)
            ->put(route('agents.update', $agent), [
                'name' => 'Agent No User Yet',
                'phone' => '03013338888',
                'cnic' => '42101-5555555-5',
                'create_login' => '1',
                'login_email' => 'agent-new-login@test.com',
                'login_password' => 'mypassword1',
            ])
            ->assertRedirect(route('team.index', ['type' => 'agents']));

        $agent->refresh();
        $user = User::where('email', 'agent-new-login@test.com')->first();

        $this->assertNotNull($user);
        $this->assertSame($user->id, $agent->user_id);
        $this->assertSame($agent->id, $user->agent_id);
        $this->assertTrue($user->hasRole('agent'));
    }

    public function test_admin_can_create_staff_user(): void
    {
        $staffRole = Role::where('slug', 'staff')
            ->where('company_id', $this->company->id)
            ->first();

        $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'New Staff',
                'email' => 'new-staff@test.com',
                'password' => 'staffpass1',
                'password_confirmation' => 'staffpass1',
                'is_active' => '1',
                'roles' => [$staffRole->id],
            ])
            ->assertRedirect(route('users.index'));

        $user = User::where('email', 'new-staff@test.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('staff'));
        $this->assertTrue($user->is_active);
        $this->assertNull($user->agent_id);
    }

    public function test_admin_can_create_user_linked_to_agent(): void
    {
        $agent = $this->createAgent('Link Me Agent');
        $agentRole = Role::where('slug', 'agent')
            ->where('company_id', $this->company->id)
            ->first();

        $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'Linked User',
                'email' => 'linked-user@test.com',
                'password' => 'linkpass1',
                'password_confirmation' => 'linkpass1',
                'is_active' => '1',
                'roles' => [$agentRole->id],
                'agent_id' => $agent->id,
            ])
            ->assertRedirect(route('users.index'));

        $user = User::where('email', 'linked-user@test.com')->first();

        $agent->refresh();
        $this->assertNotNull($user);
        $this->assertSame($agent->id, $user->agent_id);
        $this->assertSame($user->id, $agent->user_id);
        $this->assertTrue($user->hasRole('agent'));
    }

    public function test_staff_cannot_create_users(): void
    {
        $staff = $this->makeUser('staff', 'staff-no-create@test.com');
        $staffRole = Role::where('slug', 'staff')
            ->where('company_id', $this->company->id)
            ->first();

        $this->actingAs($staff)
            ->post(route('users.store'), [
                'name' => 'Nope',
                'email' => 'nope@test.com',
                'password' => 'staffpass1',
                'password_confirmation' => 'staffpass1',
                'roles' => [$staffRole->id],
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('users', 2);
    }

    public function test_agent_cannot_manage_agents(): void
    {
        $agentUser = $this->makeUser('agent', 'agent-no-manage@test.com');

        $this->actingAs($agentUser)
            ->post(route('agents.store'), [
                'name' => 'Nope',
                'phone' => '03013339999',
                'cnic' => '42101-6666666-6',
            ])
            ->assertForbidden();
    }

    public function test_subscription_and_superadmin_routes_are_removed(): void
    {
        $routes = [
            'billing.index',
            'packages.index',
            'subscriptions.index',
            'companies.index',
            'superadmin.companies.index',
        ];

        foreach ($routes as $name) {
            $this->assertEmpty(
                collect(app('router')->getRoutes())->filter(fn ($route) => $route->getName() === $name),
                "Route [{$name}] should not exist anymore."
            );
        }
    }
}
