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
            'item_name' => $data['item_name']
        ]);
    }

    public function findone($prize_id)
    {
        return Prize::find($prize_id);
    }

    public function list($data)
    {
        $auth = Auth::user();
        return  Prize::where('store_uid', $auth->uid)->paginate($data['per_page']);
    }

    public function update($prize_id, $data)
    {
        $prize = Prize::find($prize_id);
        $prize->fill($data);
        $prize->save();
    }
}
