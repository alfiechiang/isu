<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Exceptions\ErrException;
use App\Helpers\Utils;
use App\Models\AccessTokenLog;
use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
use App\Models\StorePrivilegeRoleLog;
use App\Repositories\Stores\StoreEmployeeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if ($operatorRole->name == EmployeeRole::COUNTER->value) {
            throw new ErrException('櫃檯沒有此權限操作');
        }

        $check1 = StoreEmployee::where('phone', $data['phone'])->get();
        if ($check1->isNotEmpty()) {
            throw new ErrException('手機號碼重複');
        }

        $check2 = StoreEmployee::where('email', $data['email'])->get();
        if ($check2->isNotEmpty()) {
            throw new ErrException('信箱重複');
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
                if ($operatorRole->name == EmployeeRole::TOP->value) {
                    throw new ErrException('最高權限沒有此權限操作');
                }
                $employee = Auth::user();
                $store_id = $employee->store_id;
                $store_uid = $employee->store_uid;
                $this->repository->counterRoleCreate($data, $store_id, $store_uid);
                break;
        }
    }

    public function  pageList($data)
    {
        $Builder = new StoreEmployee();
        $operatorRole = Utils::storeRole(); //操作者角色
        if ($operatorRole->name == EmployeeRole::COUNTER->value) {
            throw new ErrException('櫃檯沒有此權限操作');
        }
        if ($operatorRole->name != EmployeeRole::TOP->value) {
            $auth = Auth::user();
            //店家
            if ($operatorRole->name == EmployeeRole::STORE->value) {
                $role = StorePrivilegeRole::where('name', EmployeeRole::STORE)->first();
                $Builder = $Builder->where('store_id', $auth->store_id)->where('role_id', '>', $role->id);
            }
        }


        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('email', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('name', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('phone', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('created_at', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('updated_at', 'like', '%' . $data['keyword'] . '%');
            });
        }

        if (!empty($data['sort'])) {

            switch ($data['sort']) {
                case 'role':
                    $Builder = $Builder->orderBy('role_id', 'asc');
                    break;
                case 'time':
                    $Builder = $Builder->orderBy('created_at', 'desc')
                        ->orderBy('updated_at', 'desc');
                    break;
            }
        }

        return $Builder->paginate($data['per_page']);
    }

    public function  findOne($uid)
    {
        return StoreEmployee::where('uid', $uid)->first();
    }

    public function  delete($uid)
    {
        StoreEmployee::where('uid', $uid)->delete();
    }

    public function update($uid, $data)
    {
        
        DB::transaction(function () use ($uid, $data) {

            $employee = StoreEmployee::where('uid', $uid)->first();
            if (isset($data['role_id'])) {
                if ($employee->role_id !== $data['role_id']) {
                    $log=AccessTokenLog::where('uid',$uid)->first();
                    if(!empty($log)){
                        StorePrivilegeRoleLog::create([
                            'uid'=>$uid,
                            'access_token'=>$log->access_token
                        ]);
                    }
                }
            }

            $employee->fill($data);
            $employee->save();

        });
    }

    public function resetPassword($data)
    {
        $employee = StoreEmployee::where('email', $data['email'])->first();
        $employee->password = $data['password'];
        $employee->save();
    }
}
