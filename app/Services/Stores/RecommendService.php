<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Exceptions\ErrException;
use App\Models\Prize;
use App\Models\Recommend;
use App\Models\StorePrivilegeRole;
use Illuminate\Support\Facades\Auth;

class RecommendService
{
    public function create($data)
    { 

        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::TOP->value:
            case EmployeeRole::STORE->value:
                $data['store_uid']=$auth->store_uid;
                break;
            default:
            $data['store_uid']=null;
                break;
        }

        Recommend::create([
            'store_uid'=>$data['store_uid'],
            'type'=>$data['type'],
            'open_start_time'=>$data['open_start_time'],
            'open_end_time'=>$data['open_end_time'],
            'name'=>$data['name'],
            'precision'=>$data['precision'],
            'latitude'=>$data['latitude'],
            'phone'=>$data['phone'],
            'address'=>$data['address'],
            'official_website'=>$data['official_website'],
            'content'=>$data['content'],
            'desc'=>$data['desc'],
            'image'=>$data['image'],
        ]);

    }

    public function findone($recommend_id)
    {
        return Recommend::find($recommend_id);
    }

    public function pageList($data)
    {
        $Builder =new Recommend();
        $auth=Auth::user();
        $role=StorePrivilegeRole::find($auth->role_id);
        switch ($role->name) {
            case EmployeeRole::STORE->value:
                $Builder=$Builder->where('store_uid',$data['store_uid']);
                break;
            default:
            $data['store_uid']=null;
                break;
        }

        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('desc', 'like', '%' . $data['keyword'] . '%');
            });
        }

        return $Builder->orderBy('created_at','desc')
        ->orderBy('updated_at','desc')->paginate($data['per_page']);
     
    }

    public function update($recommend_id, $data)
    {
        $recommend = Recommend::find($recommend_id);
        $recommend->fill($data);
        $recommend->save();
    }

    public function delete($recommend_id)
    {
        $recommend = Recommend::find($recommend_id);
        $recommend->delete();
    }
}
