<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($root = config('app.url')) {
            URL::forceRootUrl($root); // keeps localhost:8000 consistent
        }
    }
}
