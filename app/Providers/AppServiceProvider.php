<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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


        if ($root = config('app.url')) {
            URL::forceRootUrl($root); // keeps localhost:8000 consistent
        }
        // 🔥 Add fingerprint() method to Request
        Request::macro('fingerprint', function () {
            $components = [
                $this->userAgent(),
                $this->header('Accept-Language'),
                $this->header('Accept-Encoding'),
            ];
            
            return hash('sha256', implode('|', array_filter($components)));
        });
    }


    
}