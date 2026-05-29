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
                'name' => 'Crunch Package',
                'price' => 250.00,
                'daily_interest_rate' => 0.5,
                'duration_days' => 150,
            ],
            'loaded' => [
                'name' => 'Loaded Package',
                'price' => 900.00,
                'daily_interest_rate' => 0.7,
                'duration_days' => 120,
            ],
            'supreme' => [
                'name' => 'Supreme Package',
                'price' => 10000.00,
                'daily_interest_rate' => 0.9,
                'duration_days' => 90,
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        return self::all()[$key] ?? null;
    }
}
