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
