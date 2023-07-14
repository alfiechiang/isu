<?php

namespace App\Services\Stores;

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
            StampCustomer::create([
                'customer_id' => $customer->id,
                'created_at' => $created_at,
                'expired_at' => $expire_at,
                'type' => StampCustomerType::SYSTEMCREATE->value
            ]);

            StampLog::create([
                'customer_id' => $customer->id,
                'created_at' => $created_at,
                'expired_at' => $expire_at,
                'operator' => $auth->name,
                'type' => StampLogType::CREATE->value,
                'desc'=>$data['desc']
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
                ->orderBy('expired_at', 'asc');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {

            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expired_at', '<=', $now)
                    ->orwhereNotNull('consumed_at');
            })
                ->where('customer_id', $customer->id)
                ->orderBy('consumed_at', 'desc');
        }

        return $Builder->paginate($data['per_page']);
    }

    public function delete($stamp_id)
    {
        $stamp = StampCustomer::find($stamp_id);
        $stamp->delete();
    }

    public function logPageList($data)
    {
        $customer = Customer::where('guid', $data['guid'])->first();
        return  StampLog::where('type', $data['type'])->where('customer_id', $customer->id)
            ->paginate($data['per_page']);
    }
}