<?php

namespace App\Services\Stores;

use App\Models\OperaterLog;
use App\Models\StoreEmployee;
use Illuminate\Support\Facades\Auth;

class OperatorLogService
{

    public function create($area,$uid,$data){
        $auth=Auth::user();
        $employee =StoreEmployee::where('uid',$uid)->first();
        OperaterLog::create([
            'operator_email' =>$auth->email,
            'email'=>$employee->email,
            'area'=>$area,
            'column'=>json_encode($data),
            'type'=>$data['type'],
            'created_at'=>date('Y-m-d H:i:s')
        ]);
    }

    public function findLatestOne($data)
    {
        return OperaterLog::where('area', $data['area'])
        ->where('email', $data['email'])->where('type', $data['type'])
        ->orderBy('created_at', 'desc')->first();
    }

}