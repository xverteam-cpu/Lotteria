<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_login_and_reach_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@lotteria.test',
            'password' => 'adminpassword',
            'is_admin' => true,
        ]);

        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'adminpassword',
        ]);

        $loginResponse->assertRedirect('/pin/setup');

        $pinResponse = $this->post('/pin/setup', [
            'pin' => '1234',
        ]);

        $pinResponse->assertRedirect('/admin/dashboard');
        $this->assertTrue(Hash::check('1234', $user->fresh()->pin_hash));
    }

    public function test_admin_dashboard_shows_user_detail_modal_sections(): void
    {
        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Jane Partner',
            'email' => 'jane@example.com',
            'last_ip_address' => '203.0.113.10',
            'region' => 'California',
            'address' => '123 Market St',
            'balance' => 150.25,
        ]);

        $user->investments()->create([
            'package_key' => 'crunch',
            'package_name' => 'Crunch',
            'package_price' => 100,
            'amount' => 100,
            'daily_interest_rate' => 2,
            'duration_days' => 30,
            'payment_method' => 'bank_transfer',
            'status' => 'approved',
            'starts_at' => now(),
        ]);

        $user->withdrawals()->create([
            'amount' => 40,
            'payment_method' => 'bank_transfer',
            'bank_name' => 'Test Bank',
            'account_number' => '123456',
            'account_holder' => 'Jane Partner',
            'status' => 'approved',
        ]);

        $user->referralEarnings()->create([
            'referred_user_id' => $user->id,
            'investment_id' => null,
            'amount' => 5,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Deposit / Investment History');
        $response->assertSee('Withdraw History');
        $response->assertSee('Income History');
        $response->assertSee('IP Location');
    }

    public function test_admin_can_update_package_slot_counts(): void
    {
        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->post('/admin/package-slots', [
                'slots' => [
                    'crunch' => 123,
                    'loaded' => 234,
                    'supreme' => 12,
                    'premium_plus' => 34,
                ],
            ]);

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('package_slots', [
            'package_key' => 'crunch',
            'remaining_slots' => 123,
        ]);

        $this->assertDatabaseHas('package_slots', [
            'package_key' => 'premium_plus',
            'remaining_slots' => 34,
        ]);
    }
}
