<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_receive_a_welcome_email(): void
    {
        Mail::fake();

        $response = $this->post(route('register.partner'), [
            'fullname' => 'Jane Doe',
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'referral' => '',
        ]);

        $response->assertRedirect(route('pin.setup'));

        $user = User::where('email', 'jane@example.com')->firstOrFail();

        Mail::assertSent(WelcomeEmail::class, function (WelcomeEmail $mail) use ($user): bool {
            return $mail->hasTo($user->email);
        });
    }
}
