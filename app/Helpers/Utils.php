<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Utils
{
    public static function getGuardName(): ?string
    {
        if (Auth::guard('customers')->check()) {
            return "customers";
        }
//        elseif (Auth::guard('stores')->check()) {
//            return "stores";
//        }

        return null;
    }
}
