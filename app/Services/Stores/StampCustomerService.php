<?php

namespace App\Services\Stores;

use App\Enums\EmployeeRole;
use App\Enums\EmployeeRoleNumber;
use App\Enums\StampCustomerType;
use App\Enums\StampLogType;
use App\Models\Customer;
use App\Models\StampCustomer;
use App\Models\StampLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StampCustomerService
{

    public function create($data)
    {

        DB::transaction(function () use ($data) {

            $customer = Customer::where('guid', $data['guid'])->first();
            $auth = Auth::user();
            $created_at = date('Y-m-d H:i:s');
            $expire_at = date('Y-m-d H:i:s', strtotime("+1 year", strtotime($created_at)));
            //$expire_at = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($created_at)));
            $type='';
            switch($auth->role_id){
                case EmployeeRoleNumber::TOP->value:
                    $type= StampCustomerType::SYSTEM_SEND->value;
                    break;
                case EmployeeRoleNumber::STORE->value:
                case EmployeeRoleNumber::COUNTER->value:
                    $type= StampCustomerType::STAY->value;
                    break;
            }
            
            StampCustomer::create([
                'customer_id' => $customer->id,
                'created_at' => $created_at,
                'expired_at' => $expire_at,
                'type' => $type,
                'source' => $auth->name,
                'memo'=>$data['desc']
            ]);

            StampLog::create([
                'customer_id' => $customer->id,
                'created_at' => $created_at,
                'expired_at' => $expire_at,
                'operator' => $auth->name,
                'type' => StampLogType::CREATE->value,
                'desc'=>$data['desc'],
                'operator_ip'=>$data['operator_ip']
            ]);

        });
    }

    public function pageList($data)
    {
        $Builder = new  StampCustomer();
        $customer = Customer::where('guid', $data['guid'])->first();
        $Builder = $Builder->where('customer_id', $customer->id);
        $now = date('Y-m-d H:i:s');

        if ($data['search'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
                ->where('expired_at', '>=', $now)->whereNull('consumed_at')
                ->orderBy('created_at', 'desc');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {

            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expired_at', '<=', $now)
                    ->orwhereNotNull('consumed_at');
            })
                ->where('customer_id', $customer->id)
                ->orderBy('consumed_at', 'desc');
        }

        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('created_at', 'like', '%' . $data['keyword'] . '%')
                ->orwhere('expired_at', 'like', '%' . $data['keyword'] . '%')
                ->orwhere('source', 'like', '%' . $data['keyword'] . '%');
            });
        }

        $Builder=$Builder->with('customer');

        return $Builder->paginate($data['per_page']);
    }

    public function delete($stamp_id,$operator_ip)
    {
        DB::transaction(function () use ($stamp_id,$operator_ip) {

            $created_at = date('Y-m-d H:i:s');
            $expire_at = date('Y-m-d H:i:s', strtotime("+1 year", strtotime($created_at)));
            $stamp = StampCustomer::find($stamp_id);
            $stamp->delete();
            $auth = Auth::user();
            StampLog::create([
                'customer_id' => $stamp->customer_id,
                'created_at' => $created_at,
                'expired_at' => $expire_at,
                'operator' => $auth->name,  
                'type' => StampLogType::DELETE->value,
                'operator_ip'=>$operator_ip
            ]);
        });
    }

    public function logPageList($data)
    {
        $customer = Customer::where('guid', $data['guid'])->first();

        $Builder =new StampLog();

        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('created_at', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('expired_at', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('operator', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('desc', 'like', '%' . $data['keyword'] . '%');
            });
        }

        return   $Builder->where('type', $data['type'])->where('customer_id', $customer->id)
            ->paginate($data['per_page']);
    }
}
