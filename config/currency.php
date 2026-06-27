<?php

return [
    'usd_to_php' => env('USD_TO_PHP_RATE', 61.31),
    'api_key' => env('EXCHANGERATE_HOST_API_KEY', '935e7cd3b311408882612b2fa4f979bc'),
    'cache_ttl' => env('CURRENCY_RATE_CACHE_TTL', 3600),
];
