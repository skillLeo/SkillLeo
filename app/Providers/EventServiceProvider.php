<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
            \SocialiteProviders\GitHub\GitHubExtendSocialite::class.'@handle',
            \SocialiteProviders\Google\GoogleExtendSocialite::class.'@handle',
            \SocialiteProviders\LinkedIn\LinkedInExtendSocialite::class.'@handle',
        ],
    ];
}