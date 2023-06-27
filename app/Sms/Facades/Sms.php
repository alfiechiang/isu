<?php

namespace App\Sms\Facades;

use App\Sms\SmsManager;
use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SmsManager::class;
    }
}
