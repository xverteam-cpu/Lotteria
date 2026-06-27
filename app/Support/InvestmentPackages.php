<?php

namespace App\Support;

class InvestmentPackages
{
    /**
     * @return array<string, array{name: string, price: float, daily_interest_rate: float, duration_days: int}>
     */
    public static function all(): array
    {
        return [
            'crunch' => [
                'name' => 'Basic',
                'price' => 120.00,
                'daily_interest_rate' => 0.60,
                'duration_days' => 180,
            ],
            'loaded' => [
                'name' => 'Standard',
                'price' => 800.00,
                'daily_interest_rate' => 0.70,
                'duration_days' => 150,
            ],
            'supreme' => [
                'name' => 'Premium',
                'price' => 4000.00,
                'daily_interest_rate' => 0.75,
                'duration_days' => 120,
            ],
            'premium_plus' => [
                'name' => 'Premium+',
                'price' => 8000.00,
                'daily_interest_rate' => 0.90,
                'duration_days' => 80,
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        return self::all()[$key] ?? null;
    }
}
