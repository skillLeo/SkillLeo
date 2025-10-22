<?php

namespace App\Providers;

use App\Listeners\LogLogout;
use App\Listeners\LogFailedLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogSuccessfulLogin;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
      
    ];
}