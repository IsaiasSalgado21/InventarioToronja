<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StorageZone;
use App\Observers\StorageZoneObserver;
use App\Models\Presentation;
use App\Observers\PresentationObserver;
use Illuminate\Pagination\Paginator;

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
        StorageZone::observe(StorageZoneObserver::class);
        Presentation::observe(PresentationObserver::class);
        Paginator::useBootstrapFive();
    }
}
