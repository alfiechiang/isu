<?php

namespace App\Providers;

use App\Broadcasting\SmsChannel;
use App\Sms\Contracts\Sendable;
use App\Sms\SmsManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        Notification::extend('sms', function($app) {
            /** @var Application $app */

            return $app->make(SmsChannel::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(SmsManager::class, function($app) {
            return new SmsManager($app);
        });

        $this->app->alias(SmsManager::class, Sendable::class);
    }
}
