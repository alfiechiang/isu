<?php

namespace App\Helpers;

use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
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

    public static  function storeRole():StorePrivilegeRole{
        $auth=Auth::user();
        $id=$auth->id;
        $employee =StoreEmployee::where('id',$id)->first();
        $role= StorePrivilegeRole::find($employee->role_id);
        return $role;
    }
}
