<?php

namespace Tests\Feature;

use App\Models\PackageSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InvestmentPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_bank_transfer_investment_is_created_as_pending_and_returns_receipt_payload(): void
    {
        $user = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['pin_verified' => true])
            ->post('/investments', [
                'package' => 'crunch',
                'amount' => 120,
                'payment_method' => 'bank_transfer',
            ], [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('investment.status', 'pending')
            ->assertJsonPath('investment.payment_method', 'bank_transfer')
            ->assertJsonPath('investment.package_name', 'Basic');

        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'package_key' => 'crunch',
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
        ]);
    }

    public function test_php_amount_is_converted_to_usd_before_investment_is_stored(): void
    {
        $user = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'balance' => 5000,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['pin_verified' => true])
            ->post('/investments', [
                'package' => 'crunch',
                'amount' => 7500,
                'currency' => 'PHP',
                'payment_method' => 'account_balance',
            ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'package_key' => 'crunch',
            'amount' => 122.33,
            'status' => 'approved',
            'payment_method' => 'account_balance',
        ]);
    }

    public function test_account_balance_activation_reduces_package_slots(): void
    {
        $user = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'balance' => 5000,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['pin_verified' => true])
            ->post('/investments', [
                'package' => 'loaded',
                'amount' => 800,
                'payment_method' => 'account_balance',
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'package_key' => 'loaded',
            'status' => 'approved',
            'payment_method' => 'account_balance',
        ]);

        $this->assertDatabaseHas('package_slots', [
            'package_key' => 'loaded',
            'remaining_slots' => 249,
        ]);
    }

    public function test_admin_send_package_activation_reduces_package_slots(): void
    {
        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $target = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->post('/admin/send-package', [
                'user_id' => $target->id,
                'package' => 'supreme',
            ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('investments', [
            'user_id' => $target->id,
            'package_key' => 'supreme',
            'status' => 'approved',
            'payment_method' => 'admin_transfer',
        ]);

        $this->assertDatabaseHas('package_slots', [
            'package_key' => 'supreme',
            'remaining_slots' => 249,
        ]);
    }

    public function test_admin_send_package_triggers_referral_commission_and_starts_immediately(): void
    {
        $referrer = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
        ]);

        $user = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'referred_by' => $referrer->id,
        ]);

        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->post('/admin/send-package', [
                'user_id' => $user->id,
                'package' => 'crunch',
            ]);

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'package_key' => 'crunch',
            'status' => 'approved',
            'payment_method' => 'admin_transfer',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $referrer->id,
            'balance' => 6.00,
        ]);
    }
}
