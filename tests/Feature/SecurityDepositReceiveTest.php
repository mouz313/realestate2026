<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Property;
use App\Models\RentAgreement;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityDepositReceiveTest extends TestCase
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

        $this->owner = $this->makeUser('owner', 'owner-dep@test.com');
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

    protected function createAgreement(float $securityDeposit): RentAgreement
    {
        $tenant = Client::create([
            'company_id' => $this->company->id,
            'name' => 'Tenant',
            'created_by' => $this->owner->id,
        ]);

        $ownerClient = Client::create([
            'company_id' => $this->company->id,
            'name' => 'Owner Client',
            'created_by' => $this->owner->id,
        ]);

        $property = Property::create([
            'company_id' => $this->company->id,
            'title' => 'Rental Flat',
            'type' => 'flat',
            'transaction_type' => 'rent',
            'status' => 'available',
            'price' => 100000,
            'owner_id' => $ownerClient->id,
            'property_code' => 'PR-DEP-TEST',
        ]);

        return RentAgreement::create([
            'company_id' => $this->company->id,
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'owner_id' => $ownerClient->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'rent_amount' => 50000,
            'security_deposit' => $securityDeposit,
            'payment_frequency' => 'monthly',
            'status' => 'active',
        ]);
    }

    public function test_partial_deposit_receive_creates_payment_and_updates_amount(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 20000,
                'method' => 'Cash',
                'reference' => 'TXN-001',
                'paid_date' => '2026-08-01',
            ])
            ->assertRedirect();

        $payment = Payment::where('rent_agreement_id', $agreement->id)->first();

        $this->assertNotNull($payment);
        $this->assertSame('security_deposit', $payment->payment_type);
        $this->assertSame('20000.00', $payment->amount);
        $this->assertNull($payment->invoice_id);
        $this->assertSame('Cash', $payment->method);

        $agreement->refresh();

        $this->assertSame('20000.00', $agreement->deposit_received_amount);
        $this->assertFalse((bool) $agreement->deposit_received);
        $this->assertSame('20000.00', $agreement->deposit_received_amount);
        $this->assertNotNull($agreement->deposit_received_date);
        $this->assertSame(30000.0, $agreement->deposit_remaining);
    }

    public function test_full_deposit_receive_marks_as_received(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 50000,
                'method' => 'Bank Transfer',
                'paid_date' => '2026-08-01',
            ])
            ->assertRedirect();

        $agreement->refresh();

        $this->assertTrue((bool) $agreement->deposit_received);
        $this->assertSame('50000.00', $agreement->deposit_received_amount);
        $this->assertSame(0.0, $agreement->deposit_remaining);
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_multiple_receipts_cumulate_and_flip_when_full(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 30000, 'method' => 'Cash', 'paid_date' => '2026-08-01',
            ])->assertRedirect();

        $agreement->refresh();
        $this->assertFalse((bool) $agreement->deposit_received);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 20000, 'method' => 'JazzCash', 'paid_date' => '2026-08-02',
            ])->assertRedirect();

        $agreement->refresh();

        $this->assertTrue((bool) $agreement->deposit_received);
        $this->assertSame('50000.00', $agreement->deposit_received_amount);
        $this->assertDatabaseCount('payments', 2);
    }

    public function test_cannot_receive_more_than_remaining(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 60000,
                'method' => 'Cash',
                'paid_date' => '2026-08-01',
            ])
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_cannot_receive_again_once_fully_received(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 50000, 'method' => 'Cash', 'paid_date' => '2026-08-01',
            ])->assertRedirect();

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 10000, 'method' => 'Cash', 'paid_date' => '2026-08-02',
            ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_agent_without_record_rent_payments_permission_is_forbidden(): void
    {
        $agreement = $this->createAgreement(50000);

        $staff = $this->makeUser('staff', 'staff-dep@test.com');
        $this->actingAs($staff)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 50000,
                'method' => 'Cash',
                'paid_date' => '2026-08-01',
            ])
            ->assertRedirect();

        $this->assertTrue((bool) $agreement->refresh()->deposit_received);
    }

    public function test_agent_cannot_receive_deposit(): void
    {
        $agent = $this->makeUser('agent', 'agent-dep@test.com');
        $agreement = $this->createAgreement(50000);

        $this->actingAs($agent)
            ->post(route('rent-agreements.deposit-receive', $agreement), [
                'amount' => 50000,
                'method' => 'Cash',
                'paid_date' => '2026-08-01',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('payments', 0);
    }
}