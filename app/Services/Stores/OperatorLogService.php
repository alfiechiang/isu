<?php

namespace App\Services\Stores;

use App\Models\Customer;
use App\Models\OperaterLog;
use App\Models\StoreEmployee;
use Illuminate\Support\Facades\Auth;

class OperatorLogService
{

    public function createStoreEmployeeLog($area,$uid,$data){
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

    public function createCustomerLog($area,$guid,$data){
        $auth=Auth::user();
        $customer =Customer::where('guid',$guid)->first();
        OperaterLog::create([
            'operator_email' =>$auth->email,
            'guid'=>$customer->guid,
            'area'=>$area,
            'column'=>json_encode($data),
            'type'=>$data['type'],
            'created_at'=>date('Y-m-d H:i:s')
        ]);
    }

    public function findLatestOne($data)
    {
        $Builder = new OperaterLog();
        $Builder=$Builder->where('area', $data['area']);

        if (isset($data['email'])){
            $Builder=$Builder->where('email', $data['email']);
        }

        if (isset($data['guid'])){
            $Builder=$Builder->where('guid', $data['guid']);
        }
        
        return $Builder->where('type', $data['type'])
        ->orderBy('created_at', 'desc')->first();
    }

}