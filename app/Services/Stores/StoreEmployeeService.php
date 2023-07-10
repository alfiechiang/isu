<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Exceptions\ErrException;
use App\Helpers\Utils;
use App\Models\StorePrivilegeRole;
use App\Repositories\Stores\StoreEmployeeRepository;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeService
{

    protected  StoreEmployeeRepository $repository;

    public function __construct(StoreEmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($data)
    {
        $operatorRole = Utils::storeRole(); //操作者角色
        if ($operatorRole->id > $data['role_id']) {  //id越小權限越大
            throw new ErrException('沒有此權限操作');
        }

        if($operatorRole->name==EmployeeRole::COUNTER){
            throw new ErrException('櫃檯沒有此權限操作');
        }

        $role = StorePrivilegeRole::find($data['role_id']);
        switch ($role->name) {
            case EmployeeRole::TOP->value:
                $this->repository->adminRoleCreate($data);
                break;
            case EmployeeRole::STORE->value:
                $this->repository->storeRoleCreate($data);
                break;
            case EmployeeRole::COUNTER->value:
                ##只有店家才能新增櫃檯
                if( $operatorRole->name==EmployeeRole::TOP){
                    throw new ErrException('最高權限沒有此權限操作');
                }
                $employee =Auth::user();
                $store_id=$employee->store_id;
                $this->repository->counterRoleCreate($data,$store_id);
                break;
        }
    }
}
