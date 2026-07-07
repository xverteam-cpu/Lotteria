<?php

namespace Tests\Feature;

use App\Mail\PackageGiftedEmail;
use App\Models\Investment;
use App\Models\User;
use App\Support\DailyInterestAccrualService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PackageGiftEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_approval_sends_a_package_gift_email_and_starts_daily_interest(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-07-01 09:00:00'));

        $admin = User::factory()->create(['is_admin' => true, 'pin_hash' => 'test-pin']);
        $recipient = User::factory()->create(['email' => 'recipient@example.com', 'balance' => 0]);

        $this->actingAs($admin)
            ->withSession(['pin_verified' => true]);

        $response = $this->post(route('admin.send-package'), [
            'user_id' => $recipient->id,
            'package' => 'loaded',
        ]);

        $response->assertRedirect();
        $investment = Investment::where('user_id', $recipient->id)->latest()->firstOrFail();

        $this->assertSame('approved', $investment->status);
        $this->assertNotNull($investment->starts_at);

        Carbon::setTestNow(Carbon::parse('2026-07-02 09:00:00'));
        $accrued = DailyInterestAccrualService::accrueDueInterestForUser($recipient->fresh());

        $this->assertEquals(5.60, round($accrued, 2));
        $this->assertEquals(5.60, round((float) $recipient->fresh()->balance, 2));

        Mail::assertSent(PackageGiftedEmail::class, function (PackageGiftedEmail $mail) use ($recipient): bool {
            return $mail->hasTo($recipient->email);
        });
    }
}
