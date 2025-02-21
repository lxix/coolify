<?php

namespace App\Providers;

use App\Events\ProxyStarted;
use App\Listeners\MaintenanceModeDisabledNotification;
use App\Listeners\MaintenanceModeEnabledNotification;
use App\Listeners\ProxyStartedNotification;
use Illuminate\Foundation\Events\MaintenanceModeDisabled;
use Illuminate\Foundation\Events\MaintenanceModeEnabled;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MaintenanceModeEnabled::class => [
            MaintenanceModeEnabledNotification::class,
        ],
        MaintenanceModeDisabled::class => [
            MaintenanceModeDisabledNotification::class,
        ],
        ProxyStarted::class => [
            ProxyStartedNotification::class,
        ],
    ];
    public function boot(): void
    {
        //
    }
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
