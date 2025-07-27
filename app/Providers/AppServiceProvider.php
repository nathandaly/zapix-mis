<?php

namespace App\Providers;

use Hidehalo\Nanoid\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Str::macro('nanoid', static function (int $size = 21, int $mode = Client::MODE_DYNAMIC): string {
            return (new Client())->generateId($size, $mode);
        });
    }
}
