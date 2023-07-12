<?php

namespace App\Services\Stores;

use App\Models\StorePrivilegeRole;

class PrivilegeRoleService
{


    public function __construct( )
    {
    }

    public function list(){
       return  StorePrivilegeRole::all();
    }

   
}
