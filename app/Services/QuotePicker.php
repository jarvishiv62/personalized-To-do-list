<?php

namespace App\Services;

use App\Models\Quote;
use Illuminate\Support\Facades\Cache;

class QuotePicker
{
    /**
     * Get the daily quote (cached for 24 hours).
     *
     * @return Quote|null
     */
    public function getDailyQuote(): ?Quote
    {
        $cacheKey = 'daily_quote_' . now()->format('Y-m-d');

        return Cache::remember($cacheKey, now()->addDay(), function () {
            return Quote::random();
        });
    }

    /**
     * Get a random quote without caching.
     *
     * @return Quote|null
     */
    public function getRandomQuote(): ?Quote
    {
        return Quote::random();
    }

    /**
     * Clear the daily quote cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        $cacheKey = 'daily_quote_' . now()->format('Y-m-d');
        Cache::forget($cacheKey);
    }
}