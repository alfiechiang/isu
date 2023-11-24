<?php

namespace App\Sms;

use App\Sms\Drivers\Every8dSms;
use App\Sms\Drivers\LogSms;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Manager;

class SmsManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string|null
     */
    public function getDefaultDriver(): ?string
    {
        return app()['config']['sms.driver'];
    }

    /**
     * @throws BindingResolutionException
     */
    public function createEvery8dDriver()
    {
        return app()->make(Every8dSms::class, [
            'config' => app()['config']['sms.every8d'],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function createLogDriver()
    {
        return app()->make(LogSms::class);
    }
}
