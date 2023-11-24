<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Exceptions\ErrException;
use App\Models\Prize;
use App\Models\StorePrivilegeRole;
use Illuminate\Support\Facades\Auth;

class PrizeService
{
    public function create($data)
    {
        $auth = Auth::user();

        $role = StorePrivilegeRole::where("name", EmployeeRole::STORE->value)->first();

        if ($auth->role_id !== $role->id) {
            throw new ErrException("沒有此權限操作");
        }

        Prize::create([
            'store_uid' => $auth->uid,
            'exchange_num' => $data['exchange_num'],
            'spend_stamp_num' => $data['spend_stamp_num'],
            'stock' => $data['stock'],
            'item_name' => $data['item_name'],
            'desc'=>$data['desc']
        ]);
    }

    public function findone($prize_id)
    {
        return Prize::find($prize_id);
    }

    public function pageList($data)
    {
        $auth = Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        $store_uid ='';
        switch($role->name){
            case EmployeeRole::STORE->value:
                $store_uid=$auth->uid;
                break;
            case EmployeeRole::COUNTER->value:
                $store_uid=$auth->store_id;
                break;
        }

        return  Prize::where('store_uid', $store_uid)->paginate($data['per_page']);
    }

    public function list()
    {
        $auth = Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        $store_uid ='';
        switch($role->name){
            case EmployeeRole::STORE->value:
                $store_uid=$auth->uid;
                break;
            case EmployeeRole::COUNTER->value:
                $store_uid=$auth->store_uid;
                break;
        }
        return  Prize::where('store_uid', $store_uid)->get();
    }

    public function update($prize_id, $data)
    {
        $prize = Prize::find($prize_id);
        $prize->fill($data);
        $prize->save();
    }

    public function delete($prize_id)
    {
        $prize = Prize::find($prize_id);
        $prize->delete();
    }
}
