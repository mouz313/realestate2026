<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array']);

        $this->company = Company::create([
            'name' => 'Test Agency',
            'slug' => 'test-agency',
            'email' => 'agency@test.com',
            'is_active' => true,
        ]);
        session(['company_id' => $this->company->id]);

        // Seed AFTER company exists so company-scoped admin roles get created.
        $this->seed(RolesAndPermissionsSeeder::class);

        $role = Role::where('slug', 'admin')
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $this->company->id))
            ->orderByRaw('company_id IS NULL')
            ->first();

        $this->admin = User::create([
            'company_id' => $this->company->id,
            'name' => 'Admin',
            'email' => 'admin-export@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->admin->roles()->attach($role->id);
    }

    public function test_deals_export_excel_returns_spreadsheet(): void
    {
        $this->actingAs($this->admin)
            ->get(route('deals.export-excel'))
            ->assertOk()
            ->assertHeader('Content-Type');
    }

    public function test_invoice_export_returns_spreadsheet(): void
    {
        $this->actingAs($this->admin)
            ->get(route('invoices.export-excel'))
            ->assertOk()
            ->assertHeader('Content-Type');
    }

    public function test_payment_export_returns_spreadsheet(): void
    {
        $this->actingAs($this->admin)
            ->get(route('payments.export-excel'))
            ->assertOk()
            ->assertHeader('Content-Type');
    }

    public function test_notifications_index_accessible(): void
    {
        $this->actingAs($this->admin)
            ->get(route('notifications.index'))
            ->assertOk();
    }
}
