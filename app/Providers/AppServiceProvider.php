<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Relation::enforceMorphMap([
            'store' => \App\Models\Store::class,
            'store_employee' => \App\Models\StoreEmployee::class,
            'stamp_customer' => \App\Models\StampCustomer::class,
            'customer' => \App\Models\Customer::class,
            'otp' => \App\Models\Otp::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
