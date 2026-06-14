<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\ReferralEarning;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'package_key',
        'package_name',
        'package_price',
        'amount',
        'payment_method',
        'daily_interest_rate',
        'duration_days',
        'starts_at',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'package_price' => 'decimal:2',
            'amount' => 'decimal:2',
            'daily_interest_rate' => 'decimal:3',
            'duration_days' => 'integer',
            'starts_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function dailyInterestAmount(): float
    {
        return (float) $this->amount * ((float) $this->daily_interest_rate / 100);
    }

    public function elapsedInterestDays(): int
    {
        if (! $this->starts_at) {
            return 0;
        }

        return min(
            $this->duration_days,
            max(0, (int) floor($this->starts_at->diffInDays(now())))
        );
    }

    public function earnedInterest(): float
    {
        return $this->dailyInterestAmount() * $this->elapsedInterestDays();
    }

    /**
     * If the investing user was referred, credit the referrer 5% commission of the capital.
     */
    public function processReferralCommission(): void
    {
        $user = $this->user;
        if (! $user || ! $user->referred_by) {
            return;
        }

        $referrer = User::find($user->referred_by);
        if (! $referrer) {
            return;
        }

        $commission = round((float) $this->amount * 0.05, 2);
        if ($commission <= 0) {
            return;
        }

        // Credit referrer balance
        $referrer->balance = ($referrer->balance ?? 0) + $commission;
        $referrer->save();

        // Record the referral earning
        ReferralEarning::create([
            'user_id' => $referrer->id,
            'referred_user_id' => $user->id,
            'investment_id' => $this->id,
            'amount' => $commission,
        ]);
    }
}
