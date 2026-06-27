<?php

namespace Tests\Feature;

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
            ->assertJsonPath('investment.package_name', 'Crunch Package - Basic Share');

        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'package_key' => 'crunch',
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
        ]);
    }
}
