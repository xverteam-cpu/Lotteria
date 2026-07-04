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

    public function test_admin_can_delete_a_user_account_from_the_details_page(): void
    {
        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $userToDelete = User::factory()->create([
            'name' => 'Delete Me',
            'email' => 'delete-me@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->delete(route('admin.users.destroy', $userToDelete));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('status', 'User account deleted successfully.');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_restrict_a_user_from_accessing_the_site(): void
    {
        $admin = User::factory()->create([
            'pin_hash' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'last_ip_address' => '203.0.113.10',
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['pin_verified' => true])
            ->post(route('admin.users.restrict', $user));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('status', 'User access restricted successfully.');

        $this->assertTrue($user->fresh()->is_restricted);
        $this->assertSame('203.0.113.10', $user->fresh()->restricted_ip_address);

        $blockedResponse = $this->actingAs($user->fresh())
            ->withSession(['pin_verified' => true])
            ->get('/dashboard');

        $blockedResponse->assertRedirect('/unavailable');
    }
}
