<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PortalAccessGranted;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotificationDispatchTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        // Avoid real mail transport during DB-notification dispatch tests.
        config(['mail.default' => 'array']);

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
            'email' => strtolower($roleSlug).'-notif@test.com',
            'password' => Hash::make('password'),
            'role' => $roleSlug,
            'agent_id' => $agent?->id,
            'is_active' => true,
        ]);
        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_portal_access_granted_notifies_client_and_admins(): void
    {
        $admin = $this->makeUser('admin');
        $client = Client::create([
            'company_id' => $this->company->id,
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'phone' => '03001234567',
        ]);

        notify_company($this->company, PortalAccessGranted::class, [$client], [$client]);

        $this->assertCount(1, $admin->notifications()->get());
        $this->assertCount(1, $client->notifications()->get());
    }

    public function test_notification_includes_url_and_title_data(): void
    {
        $client = Client::create([
            'company_id' => $this->company->id,
            'name' => 'Test Client',
            'email' => 'client2@test.com',
            'phone' => '03001234567',
        ]);

        notify_company($this->company, PortalAccessGranted::class, [$client], [$client]);

        $data = $client->notifications()->first()->data;

        $this->assertEquals('Portal access granted', $data['title']);
        $this->assertStringContainsString(route('portal.login'), $data['url']);
    }
}
