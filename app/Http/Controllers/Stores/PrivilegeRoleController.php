<?php

namespace App\Http\Controllers\Stores;

use App\Services\Stores\PrivilegeRoleService;
use Illuminate\Http\Request;

class PrivilegeRoleController extends Controller
{

    protected PrivilegeRoleService $privilegeRoleService;

    public function __construct(PrivilegeRoleService $privilegeRoleService)
    {
        $this->privilegeRoleService=$privilegeRoleService;
    }

    public function list(Request $request){
        return $this->privilegeRoleService->list();
    }

}
