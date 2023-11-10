<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Enums\PointLogType;
use App\Enums\PotintCustomerTye;
use App\Models\Customer;
use App\Models\PointCustomer;
use App\Models\PointLog;
use App\Models\StorePrivilegeRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ErrException;


class PointCustomerService
{

    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $customer = Customer::where('guid', $data['guid'])->first();
            $customer->point_balance+=$data['points'];
            $customer->save();
            $auth = Auth::user();
            $role = StorePrivilegeRole::find($auth->role_id);
            switch ($role->name) {
                case EmployeeRole::TOP->value:
                    $data['source'] = '系統';
                    $data['type'] = PotintCustomerTye::SYSTEM_CREATE->value;
                    break;
                case EmployeeRole::STORE->value:
                    $data['source'] = $auth->name;
                    $data['type']=PotintCustomerTye::CONSUME->value;
                    break;
                case EmployeeRole::COUNTER->value:
                    $data['source'] = $auth->name;
                    $data['type']=PotintCustomerTye::CONSUME->value;
                    break;
            }

            PointCustomer::create([
                'value' => $data['points'],
                'customer_id' => $customer->id,
                'desc' => $data['desc'],
                'source' => $data['source'],
                'type'=>$data['type'],
                'operator'=>$auth->email
            ]);

           
            PointLog::create([
                'customer_id' => $customer->id,
                'type' => PointLogType::CREATE->value,
                'created_at' => date('Y-m-d H:i:s'),
                'points_num' => $data['points'],
                'operator' => $auth->name,
                'desc' => $data['desc']
            ]);
        });
    }

    public function delete( $data)
    {
        DB::transaction(function () use ( $data) {
            $customer = Customer::where('guid', $data['guid'])->first();
            $customer->point_balance-=$data['points'];
            if ($customer->point_balance < 0) {
                throw new ErrException('扣除點數已超過已有點數');
            }
            $customer->save();
            $auth = Auth::user();
            PointCustomer::create([
                'value' => -$data['points'],
                'customer_id' => $customer->id,
                'desc' => $data['desc'],
                'source' => '系統',
                'type'=>PotintCustomerTye::SYSTEM_DELETE->value,
                'operator'=>$auth->email
            ]);
        });
    }


    public function minus($data)
    {


        DB::transaction(function () use ($data) {
            $customer = Customer::where('guid', $data['guid'])->first();
            $customer->point_balance-=$data['points'];
            if($customer->point_balance<0){
                throw new ErrException('扣除點數超過持有點數');
            }
            $customer->save();
            //類型 1:兌換集章2:進店掃描3:消費認證4:系統新增5系統扣除
        });

    
    }


    

    public function list($data)
    {
        $customer = Customer::where('guid', $data['guid'])->first();
        $Builder = PointCustomer::where('customer_id', $customer->id);
        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('created_at', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('source', 'like', '%' . $data['keyword'] . '%');
            });
        }

        return $Builder->orderBy('created_at','desc')->paginate($data['per_page']);
    }

    public function totalPoints($data)
    {
        $customer = Customer::where('guid', $data['guid'])->first();

        return ['total' => $customer->point_balance];
    }

    public function logPageList($data)
    {
        $customer = Customer::where('guid', $data['guid'])->first();

        $Builder=PointLog::where('type', $data['type'])->where('customer_id', $customer->id);
        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('created_at', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('operator', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('desc', 'like', '%' . $data['keyword'] . '%');
            });
        }
        return $Builder->orderBy('created_at','desc')->paginate($data['per_page']);
    }
}
