<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // No need for persistence configuration as Cache handles this
        
        // Define helper functions for JSON data in cache
        if (!function_exists('cache_set_json')) {
            function cache_set_json($key, $value, $ttl = null) {
                return Cache::put($key, json_encode($value), $ttl);
            }
        }
        
        if (!function_exists('cache_get_json')) {
            function cache_get_json($key) {
                $value = Cache::get($key);
                return $value ? json_decode($value, true) : null;
            }
        }
    }
}
