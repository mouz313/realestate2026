<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Company;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AgentScopeTest extends TestCase
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

        $this->agentA = Agent::create(['company_id' => $this->company->id, 'name' => 'Agent A', 'phone' => '03000000001', 'cnic' => 'A1-CNIC', 'status' => 'active']);
        $this->agentB = Agent::create(['company_id' => $this->company->id, 'name' => 'Agent B', 'phone' => '03000000002', 'cnic' => 'B1-CNIC', 'status' => 'active']);

        $this->userA = $this->makeAgentUser($this->agentA);
        $this->userB = $this->makeAgentUser($this->agentB);
    }

    protected function makeAgentUser(Agent $agent): User
    {
        $role = Role::where('slug', 'agent')
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $this->company->id))
            ->orderByRaw('company_id IS NULL')
            ->first();

        $user = User::create([
            'company_id' => $this->company->id,
            'name' => $agent->name,
            'email' => strtolower(str_replace(' ', '', $agent->name)).'@test.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'agent_id' => $agent->id,
            'is_active' => true,
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    protected function createProperty(int $agentId, string $title): Property
    {
        $client = Client::create([
            'company_id' => $this->company->id,
            'name' => 'Owner '.$title,
            'created_by' => $this->userA->id,
        ]);

        return Property::create([
            'company_id' => $this->company->id,
            'title' => $title,
            'type' => 'house',
            'transaction_type' => 'sale',
            'status' => 'available',
            'price' => 1000000,
            'owner_id' => $client->id,
            'assigned_agent_id' => $agentId,
            'property_code' => 'PR-TEST-'.$title,
        ]);
    }

    public function test_agent_only_sees_own_properties(): void
    {
        $this->createProperty($this->agentA->id, 'Alpha House');
        $this->createProperty($this->agentB->id, 'Beta House');

        $this->actingAs($this->userA);

        $visible = Property::pluck('title')->all();

        $this->assertContains('Alpha House', $visible);
        $this->assertNotContains('Beta House', $visible);
        $this->assertCount(1, $visible);
    }

    public function test_agent_cannot_access_other_agents_property(): void
    {
        $property = $this->createProperty($this->agentB->id, 'Beta House');

        $this->actingAs($this->userA);

        // Route model binding + global scope => 404 (not found in agent's scope)
        $this->get(route('properties.show', $property))->assertNotFound();
    }

public function test_admin_sees_all_properties(): void
    {
        $this->createProperty($this->agentA->id, 'Alpha House');
        $this->createProperty($this->agentB->id, 'Beta House');

        $ownerRole = Role::where('slug', 'owner')
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $this->company->id))
            ->orderByRaw('company_id IS NULL')
            ->first();

        $owner = User::create([
            'company_id' => $this->company->id,
            'name' => 'Owner',
            'email' => 'owner@test.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);
        $owner->roles()->attach($ownerRole->id);

        $this->actingAs($owner);

        $this->assertCount(2, Property::all());
    }
}