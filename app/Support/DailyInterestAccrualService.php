<?php

namespace App\Support;

use App\Models\Investment;
use Illuminate\Support\Carbon;

class DailyInterestAccrualService
{
    public static function accrueDueInterest(): float
    {
        $totalCredited = 0.0;

        $investments = Investment::query()
            ->where('status', 'approved')
            ->whereNotNull('starts_at')
            ->where('starts_at', '<=', now())
            ->get();

        foreach ($investments as $investment) {
            $totalCredited += $investment->accrueDailyInterest();
        }

        return round($totalCredited, 2);
    }
}
