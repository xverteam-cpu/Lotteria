<?php

namespace App\Support;

use App\Models\PackageSlot;

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

    public static function defaults(): array
    {
        return [
            'crunch' => 250,
            'loaded' => 250,
            'supreme' => 250,
            'premium_plus' => 250,
        ];
    }

    public static function currentSlots(): array
    {
        $slots = PackageSlot::query()
            ->pluck('remaining_slots', 'package_key')
            ->toArray();

        foreach (self::defaults() as $key => $default) {
            if (! isset($slots[$key])) {
                $slots[$key] = $default;
            }
        }

        return $slots;
    }

    public static function reserveSlot(string $key): bool
    {
        $default = self::defaults()[$key] ?? 250;
        $slot = PackageSlot::firstOrCreate([
            'package_key' => $key,
        ], [
            'remaining_slots' => $default,
        ]);

        if ($slot->remaining_slots <= 0) {
            return false;
        }

        return (bool) $slot->decrement('remaining_slots');
    }
}
