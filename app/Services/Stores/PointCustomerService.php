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

class PointCustomerService
{

    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $customer = Customer::where('guid', $data['guid'])->first();
            $auth = Auth::user();
            $role = StorePrivilegeRole::find($auth->role_id);
            switch ($role->name) {
                case EmployeeRole::TOP->value:
                    $data['source'] = '系統發放';
                    $data['type'] = 0;
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
                'type'=>$data['type']
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

    public function delete($point_id, $data)
    {
        DB::transaction(function () use ($point_id, $data) {

            $customer = Customer::where('guid', $data['guid'])->first();
            $point = PointCustomer::find($point_id);
            $point->delete();
            $auth = Auth::user();
            PointLog::create([
                'customer_id' => $customer->id,
                'type' => PointLogType::DELETE->value,
                'created_at' => date('Y-m-d H:i:s'),
                'points_num' => $point->value,
                'operator' => $auth->name,
                'desc' => $data['desc']
            ]);
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
        $rows =  PointCustomer::select('customer_id', DB::raw('SUM(value) as total'))
            ->where('customer_id', $customer->id)->where('is_redeem', false)
            ->groupBy('customer_id')->get();

        $total = intval($rows[0]->total);

        return ['total' => $total];
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
