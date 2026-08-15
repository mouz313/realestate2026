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

class MoveOutAndDepositReturnTest extends TestCase
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

        $this->owner = $this->makeUser('owner', 'owner-move@test.com');
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
            'status' => 'rented',
            'price' => 100000,
            'owner_id' => $ownerClient->id,
            'property_code' => 'PR-MOVE-TEST',
        ]);

        $agreement = RentAgreement::create([
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

        $agreement->generateSchedule();

        return $agreement;
    }

    public function test_move_out_terminates_waives_future_installments_and_releases_property(): void
    {
        $agreement = $this->createAgreement(50000);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.move-out', $agreement), [
                'possession_returned_date' => '2026-06-30',
            ])
            ->assertRedirect();

        $agreement->refresh();

        $this->assertSame('terminated', $agreement->status);
        $this->assertSame('2026-06-30', $agreement->possession_returned_date->toDateString());
        $this->assertSame('2026-06-30', $agreement->end_date->toDateString());
        $this->assertSame('available', $agreement->property->status);

        $this->assertSame(6, $agreement->rentPayments()->where('status', 'waived')->count());
        $this->assertSame(6, $agreement->rentPayments()->where('status', 'pending')->count());
    }

    public function test_deductions_are_itemized_and_totalled(): void
    {
        $agreement = $this->createAgreement(50000);
        $agreement->update(['status' => 'terminated']);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deductions.store', $agreement), [
                'category' => 'damage',
                'title' => 'Broken window',
                'amount' => 5000,
            ])
            ->assertRedirect();

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deductions.store', $agreement), [
                'category' => 'unpaid_rent',
                'title' => 'Unpaid June rent',
                'amount' => 5000,
            ])
            ->assertRedirect();

        $agreement->refresh();
        $this->assertSame('10000.00', $agreement->deposit_deductions);
        $this->assertSame(2, $agreement->depositDeductions()->count());

        $deduction = $agreement->depositDeductions()->first();

        $this->actingAs($this->owner)
            ->delete(route('rent-agreements.deductions.destroy', [$agreement, $deduction]))
            ->assertRedirect();

        $agreement->refresh();
        $this->assertSame('5000.00', $agreement->deposit_deductions);
        $this->assertSame(1, $agreement->depositDeductions()->count());
    }

    public function test_return_deposit_creates_outgoing_payment_of_net_amount(): void
    {
        $agreement = $this->createAgreement(50000);
        $agreement->update([
            'deposit_received' => true,
            'deposit_received_amount' => 50000,
            'deposit_received_date' => '2026-01-05',
        ]);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.move-out', $agreement), [
                'possession_returned_date' => '2026-06-30',
            ])->assertRedirect();

        $agreement->refresh();

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deductions.store', $agreement), [
                'category' => 'damage',
                'title' => 'Repaint cost',
                'amount' => 10000,
            ])->assertRedirect();

        $agreement->refresh();
        $this->assertSame(40000.0, $agreement->net_deposit_return);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.return-deposit', $agreement), [
                'method' => 'Bank Transfer',
                'reference' => 'RTN-001',
                'paid_date' => '2026-07-05',
            ])
            ->assertRedirect();

        $agreement->refresh();

        $this->assertTrue((bool) $agreement->deposit_returned);
        $this->assertNotNull($agreement->deposit_returned_date);

        $payment = Payment::where('rent_agreement_id', $agreement->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame('security_deposit_return', $payment->payment_type);
        $this->assertSame('40000.00', $payment->amount);
        $this->assertNull($payment->invoice_id);
        $this->assertSame('Bank Transfer', $payment->method);
    }

    public function test_no_return_when_deductions_exceed_received(): void
    {
        $agreement = $this->createAgreement(50000);
        $agreement->update([
            'status' => 'terminated',
            'deposit_received' => true,
            'deposit_received_amount' => 50000,
            'deposit_received_date' => '2026-01-05',
        ]);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.deductions.store', $agreement), [
                'category' => 'other',
                'title' => 'Heavy damages',
                'amount' => 60000,
            ])->assertRedirect();

        $agreement->refresh();
        $this->assertSame(0.0, $agreement->net_deposit_return);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.return-deposit', $agreement), [
                'method' => 'Cash',
                'paid_date' => '2026-07-05',
            ])
            ->assertRedirect();

        $agreement->refresh();

        $this->assertFalse((bool) $agreement->deposit_returned);
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_no_return_when_no_deposit_received(): void
    {
        $agreement = $this->createAgreement(50000);
        $agreement->update(['status' => 'terminated']);

        $this->actingAs($this->owner)
            ->post(route('rent-agreements.return-deposit', $agreement), [
                'method' => 'Cash',
                'paid_date' => '2026-07-05',
            ])
            ->assertRedirect();

        $agreement->refresh();

        $this->assertFalse((bool) $agreement->deposit_returned);
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_agent_without_settle_deposits_permission_is_forbidden(): void
    {
        $agreement = $this->createAgreement(50000);

        $agent = $this->makeUser('agent', 'agent-move@test.com');

        $this->actingAs($agent)
            ->post(route('rent-agreements.move-out', $agreement), [
                'possession_returned_date' => '2026-06-30',
            ])
            ->assertForbidden();

        $this->actingAs($agent)
            ->post(route('rent-agreements.deductions.store', $agreement), [
                'category' => 'damage',
                'title' => 'Broken window',
                'amount' => 1000,
            ])
            ->assertForbidden();

        $this->actingAs($agent)
            ->post(route('rent-agreements.return-deposit', $agreement), [
                'method' => 'Cash',
                'paid_date' => '2026-07-05',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('rent_deposit_deductions', 0);
        $this->assertDatabaseCount('payments', 0);
        $this->assertNotSame('terminated', $agreement->refresh()->status);
    }
}