<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyRateService
{
    public static function latestUsdToPhp(): float
    {
        return Cache::remember(
            self::cacheKey(),
            config('currency.cache_ttl', 3600),
            fn () => self::fetchFromApi()
        );
    }

    private static function cacheKey(): string
    {
        return 'usd_to_php_rate';
    }

    private static function fetchFromApi(): float
    {
        $endpoint = 'https://api.exchangerate.host/latest';
        $apiKey = config('currency.api_key');

        $response = Http::acceptJson()->get($endpoint, [
            'base' => 'USD',
            'symbols' => 'PHP',
            'access_key' => $apiKey,
        ]);

        if (! $response->successful() || $response->json('success') === false) {
            return (float) config('currency.usd_to_php', 61.31);
        }

        $rate = $response->json('rates.PHP');

        if (! is_numeric($rate) || (float) $rate <= 0) {
            return (float) config('currency.usd_to_php', 61.31);
        }

        return round((float) $rate, 4);
    }
}
