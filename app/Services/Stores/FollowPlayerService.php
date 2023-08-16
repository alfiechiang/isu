<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Models\FollowPlayer;
use App\Models\StorePrivilegeRole;
use App\Repositories\Stores\FollowPlayerRepository;
use Illuminate\Support\Facades\Auth;

class FollowPlayerService
{

    protected  FollowPlayerRepository $repository;

    public function __construct(FollowPlayerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($data)
    {
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::TOP->value:
                $this->repository->adminRoleCreate($data);
                break;
            case EmployeeRole::STORE->value:
                $data['store_uid']=$auth->store_uid;
                $this->repository->storeRoleCreate($data);
                break;
            case EmployeeRole::COUNTER->value:
                $data['store_uid']=$auth->store_uid;
                $this->repository->counterRoleCreate($data);
                break;
        }
    }
    public function update($follow_id,$data)
    {
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::COUNTER->value:
                $data['review'] = false;
            break;
        }
        $follower =FollowPlayer::find($follow_id);
        $follower->fill($data);
        $follower->save();
    }

    public function findone($follow_id)
    {
        $follower =FollowPlayer::find($follow_id);
        return $follower;
    }

    public function pageList($data)
    {
        $Builder = new FollowPlayer();
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::STORE->value:
            case EmployeeRole::COUNTER->value:
                $data['store_uid']=$auth->store_uid;
                $Builder= $Builder->where('store_uid',$data['store_uid']);
                break;
        }
        if(isset($data['review'])){
            $Builder= $Builder->where('review',$data['review']);
        }
        return $Builder->orderBy('created_at','desc')
        ->orderBy('updated_at','desc')->paginate($data['per_page']);

    }

}
