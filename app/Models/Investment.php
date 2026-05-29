<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected function casts(): array
    {
        return [
            'package_price' => 'decimal:2',
            'amount' => 'decimal:2',
            'daily_interest_rate' => 'decimal:3',
            'duration_days' => 'integer',
            'starts_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
