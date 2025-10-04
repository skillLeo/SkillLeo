<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // Standard LinkedIn provider with OpenID Connect support
            \SocialiteProviders\LinkedIn\LinkedInExtendSocialite::class.'@handle',
        ],
    ];

    public function boot(): void
    {
        //
    }
}