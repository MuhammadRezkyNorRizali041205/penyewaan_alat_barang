<?php

namespace App\Providers;

use App\Models\Alat;
use App\Models\Penyewaan;
use App\Models\User;
use App\Observers\AlatObserver;
use App\Observers\PenyewaanObserver;
use App\Policies\AlatPolicy;
use App\Policies\PenyewaanPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        // Register model observers
        Penyewaan::observe(PenyewaanObserver::class);
        Alat::observe(AlatObserver::class);

        // Register policy
        Gate::policy(Alat::class, AlatPolicy::class);
        Gate::policy(Penyewaan::class, PenyewaanPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
