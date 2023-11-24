<?php

namespace App\Services\Stores;

use App\Models\StorePrivilegeIntermediate;
use App\Models\StorePrivilegeMenu;
use Illuminate\Support\Facades\Auth;

class  StorePrivilegeMenuService
{

    public function list()
    {
        $auth = Auth::user();
        $role_id = $auth->role_id;
        $menu_ids = StorePrivilegeIntermediate::where('role_id', $role_id)->pluck('menu_id');
        return  StorePrivilegeMenu::whereIn('id', $menu_ids)->get();
    }
}
