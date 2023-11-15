<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Models\County;
use App\Models\FollowPlayer;
use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
use App\Repositories\Stores\FollowPlayerRepository;
use ErrorException;
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
        $follower =FollowPlayer::find($follow_id);

        if(isset($data['area'])){
            $areas = County::select('region')->groupBy('region')->pluck('region')->toArray();
            if(!in_array($data['area'], $areas)){
                throw new ErrorException("傳送資料有誤");
            }
        }

       

        if( $follower->area )
        switch ($role->name) {
            case EmployeeRole::STORE->value:
                $data['review'] = true;
                break;
            case EmployeeRole::COUNTER->value:
                $operator =$follower->operator;
                $dignity=StoreEmployee::where('email',$operator)->first();
                $dignityRole=StorePrivilegeRole::find($dignity->role_id);
                if($dignityRole->name ==EmployeeRole::STORE->value){
                    throw new ErrorException("櫃檯沒有權限編輯店家文章") ;
                }

                if($follower->creator !== $auth->email){
                    throw new ErrorException("櫃檯沒有權限編輯其他櫃檯文章") ;
                }
                if($follower->review){
                    throw new ErrorException("已發布櫃檯沒有權限編輯") ;

                }
                break;
        }
        $follower->fill($data);
        $follower->save();
    }

    public function checkUpdatePermission($follow_id){

        $hasPermission=true;
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        $follower =FollowPlayer::find($follow_id);

        switch ($role->name) {
            case EmployeeRole::COUNTER->value:
                $operator =$follower->operator;
                $dignity=StoreEmployee::where('email',$operator)->first();
                $dignityRole=StorePrivilegeRole::find($dignity->role_id);
                if($dignityRole->name ==EmployeeRole::STORE->value){
                    $hasPermission=false;
                }
                break;
            default:
            break;
        }

        return ['hasPermission' =>$hasPermission];
    }

    

    public function findone($follow_id)
    {
        $follower =FollowPlayer::find($follow_id);
        return $follower;
    }

    public function delete($follow_id)
    {
        $follower =FollowPlayer::find($follow_id);
        $auth=Auth::user();

        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::COUNTER->value:
                if($follower->creator!==$auth->email){
                    throw new ErrorException("櫃檯沒有權限刪除其他櫃檯文章") ;
                }
            break;
        }
        $follower->delete();
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

        if (isset($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('title', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('sub_title', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('artist', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('operator', 'like', '%' . $data['keyword'] . '%');
            });
        }
        
        return $Builder->orderBy('created_at','desc')
        ->orderBy('updated_at','desc')->paginate($data['per_page']);

    }

    //自己所屬的文章
    public function ownList($data)
    {
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        $Builder = new FollowPlayer();
        $follower_ids=[];
        $email=(Auth::user())->email;
        switch ($role->name) {
            case EmployeeRole::STORE->value:
                $follower_ids=$Builder->where('store_uid',$auth->store_uid)->pluck('id');
                break;
            case EmployeeRole::COUNTER->value:
                $follower_ids=$Builder->where('store_uid',$auth->store_uid)
                ->where('creator',$email)->pluck('id');
                break;
        }

        return ['follower_ids'=>$follower_ids];

    }

}
