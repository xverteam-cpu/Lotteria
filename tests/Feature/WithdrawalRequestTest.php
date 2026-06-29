<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WithdrawalRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_provide_bank_details_before_requesting_withdrawal(): void
    {
        $user = User::factory()->create([
            'balance' => 500,
            'pin_hash' => Hash::make('1234'),
        ]);

        $this->actingAs($user);
        $this->withSession(['pin_verified' => true]);

        $response = $this->from('/withdraw')->post('/withdrawals', [
            'amount' => 50,
        ]);

        $response->assertSessionHasErrors(['bank_name', 'account_number', 'account_holder']);
        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_user_can_submit_withdrawal_request_with_bank_details(): void
    {
        $user = User::factory()->create([
            'balance' => 500,
            'pin_hash' => Hash::make('1234'),
        ]);

        $this->actingAs($user);
        $this->withSession(['pin_verified' => true]);

        $response = $this->from('/withdraw')->post('/withdrawals', [
            'amount' => 50,
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'account_holder' => 'Test User',
        ]);

        $response->assertRedirect(route('withdraw'));
        $response->assertSessionHas('status', 'Withdrawal request submitted successfully.');
        $this->assertDatabaseHas('withdrawals', [
            'user_id' => $user->id,
            'amount' => 50.00,
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'account_holder' => 'Test User',
            'status' => 'pending',
        ]);

        $this->assertSame('Test Bank', $user->fresh()->bank_name);
        $this->assertSame('1234567890', $user->fresh()->bank_account_number);
        $this->assertSame('Test User', $user->fresh()->bank_account_holder);
    }
}
