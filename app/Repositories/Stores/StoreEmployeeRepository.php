<?php

namespace App\Repositories\Stores;

use App\Models\Store;
use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;

class StoreEmployeeRepository {


    public function adminRoleCreate($data){
       $role= StorePrivilegeRole::where('name','TOP')->first();
        StoreEmployee::create([
            'email'=>$data['email'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>$data['password'],
            'desc'=>$data['desc'],
            'role_id'=>$role->id
        ]);
    }

    public function storeRoleCreate($data){
        $role= StorePrivilegeRole::where('name','STORE')->first();
        $uid='ISU'. rand(100000, 999999);
        
        $store=Store::create([
            'name'=>$data['name'],
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
        ]);

    }

    public function counterRoleCreate($data,$store_id){
        $role= StorePrivilegeRole::where('name','COUNTER')->first();
        StoreEmployee::create([
            'email'=>$data['email'],
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>$data['password'],
            'desc'=>$data['desc'],
            'role_id'=>$role->id,
            'store_id'=>$store_id
        ]);

    }

}
