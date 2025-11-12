<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StorageZone;
use App\Observers\StorageZoneObserver;
use App\Models\Presentation;
use App\Observers\PresentationObserver;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
        Gate::define('is-employee', function (User $user) {
            return $user->role === 'user';
        });
        StorageZone::observe(StorageZoneObserver::class);
        Presentation::observe(PresentationObserver::class);
        Paginator::useBootstrapFive();
    }
}
