<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Vite;

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
        // 1. Set Pagination to use Bootstrap 5
        Paginator::useBootstrap();

        // 2. Load custom helper functions
        if (file_exists($file = app_path('helpers.php'))) {
            require_once $file;
        }

        // 3. Force Vite scripts to use 'defer' for better performance
        Vite::useScriptTagAttributes([
            'defer' => true,
        ]);
    }
}