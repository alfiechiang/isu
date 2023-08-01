<?php

namespace App\Repositories\Stores;

use App\Models\Store;
use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeRepository {


    public function adminRoleCreate($data){
       $role= StorePrivilegeRole::where('name','TOP')->first();
       $uid='ISU'. rand(100000, 999999);
        StoreEmployee::create([
            'email'=>$data['email'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>$data['password'],
            'desc'=>$data['desc'],
            'role_id'=>$role->id,
            'uid'=>$uid,
            'country_code'=>$data['country_code']
        ]);
    }

    public function storeRoleCreate($data){
        $role= StorePrivilegeRole::where('name','STORE')->first();
        $uid='ISU'. rand(100000, 999999);

        $store=Store::create([
            'name'=>$data['name']
        ]);
        
        StoreEmployee::create([
            'email'=>$data['email'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>$data['password'],
            'desc'=>$data['desc'],
            'role_id'=>$role->id,
            'store_id'=>$store->id,
            'uid'=>$uid,
            'store_uid'=>$uid,
            'country_code'=>$data['country_code']
        ]);

    }

    public function counterRoleCreate($data,$store_id,$store_uid){
        $role= StorePrivilegeRole::where('name','COUNTER')->first();
        $uid='ISU'. rand(100000, 999999);
        StoreEmployee::create([
            'email'=>$data['email'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>$data['password'],
            'desc'=>$data['desc'],
            'role_id'=>$role->id,
            'store_id'=>$store_id,
            'store_uid'=>$store_uid,
            'uid'=>$uid,
            'country_code'=>$data['country_code']
        ]);

    }

}
