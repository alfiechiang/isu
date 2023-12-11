<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;

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
        DB::listen(function (QueryExecuted $event) {
            $sql = $event->sql;
            $bindings = $event->bindings;
            $time = $event->time;

            // Format the query with bindings
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }
            $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
            $sql = vsprintf($sql, $bindings);

            Log::channel('sql')->info("{$sql} [{$time} ms]");
        });
    }
}
