<?php

namespace App\Providers;

use App\Models\Meter;
use App\Models\Site;
use App\Contracts\BillingPreferenceServiceInterface;
use App\Contracts\ConsumptionServiceInterface;
use App\Contracts\DashboardServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Services\BillingPreferenceService;
use App\Services\ConsumptionService;
use App\Services\DashboardService;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind interfaces to concrete services
        $this->app->bind(ConsumptionServiceInterface::class, ConsumptionService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(BillingPreferenceServiceInterface::class, BillingPreferenceService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('site', function ($value) {
            $user = request()->user();
        
            if (!$user) {
                abort(401, 'Unauthenticated');
            }
        
            return \App\Models\Site::query()
                ->whereKey($value)
                ->where('customer_id', $user->customer_id)
                ->firstOrFail();
        });
        
        Route::bind('meter', function ($value) {
            $user = request()->user();
        
            if (!$user) {
                abort(401, 'Unauthenticated');
            }
        
            return \App\Models\Meter::query()
                ->whereKey($value)
                ->whereHas('site', fn ($q) => $q->where('customer_id', $user->customer_id))
                ->firstOrFail();
        });
    }
}
