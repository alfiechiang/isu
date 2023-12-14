<?php

namespace App\Services\Customers;

use App\Exceptions\ErrException;
use App\Models\Customer;
use App\Models\StampCustomer;
use Illuminate\Support\Facades\Auth;
use App\Enums\StampCustomerType;
use App\Models\Prize;
use App\Models\StoreEmployee;
use Illuminate\Support\Facades\DB;

class StampService
{

    public function list($data)
    {

        $auth = Auth::user();
        $Builder = new  StampCustomer();
        $Builder = $Builder->where('customer_id', $auth->id);
        $now = date('Y-m-d H:i:s');

        if ($data['search'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
                ->where('expired_at', '>=', $now)->whereNull('consumed_at');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {
            $Builder = $Builder->where('expired_at', '<=', $now)->orwhereNotNull('consumed_at')
                ->where('customer_id', $auth->id)
                ->orderBy('consumed_at', 'desc');
        }


        return $Builder->where('customer_id', $auth->id)->get();
    }

    public function pageList($data)
    {
        $Builder = new  StampCustomer();
        $auth = Auth::user();
        $Builder = $Builder->where('customer_id', $auth->id);
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
                ->where('customer_id', $auth->id)
                ->orderBy('consumed_at', 'desc');
        }

        return $Builder->paginate($data['per_page']);
    }

    #贈送集章
    public function deliver($data)
    {
        $cutomer = Customer::where('guid', $data['guid'])->first();

        if (is_null($cutomer)) {
            throw new ErrException("該ID不存在");
        }
        $to_cutomer = $cutomer->id;
        $auth = Auth::user();
        $from_cutomer = $auth->id;
        $stamps = StampCustomer::where('customer_id', $from_cutomer)->get();

        if ($data['stamp_num'] > $stamps->count()) {
            throw new ErrException("數量不足");
        }

        StampCustomer::where('customer_id', $from_cutomer)->limit($data['stamp_num'])
            ->whereNull('consumed_at')
            ->orderBy('expired_at', 'asc')->update([
                'customer_id' => $to_cutomer,
                'source' => $auth->guid,
                'type' => 3, //他人贈送
                'created_at'=>date('Y-m-d H:i:s')
            ]);

        for ($i = 0; $i < $data['stamp_num']; $i++) {
            StampCustomer::create([
                'customer_id' => $from_cutomer,
                'type' => 7, //7:已使用（我方贈予）
                'consumed_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function exchangeStamp($data)
    {
        DB::transaction(function () use ($data) {
            $prize = Prize::find($data['prize_id'])->first();
            if ($data['exchange_num'] > $prize->stock) {
                throw new ErrException("品項庫存不足");
            }
            $prize->stock = $prize->stock - $data['exchange_num'];
            $prize->save();

            $spend_stamp_num = $data['spend_stamp_num'];
            $now = date('Y-m-d H:i:s');
            
            $customer = Customer::where("guid", $data['guid'])->first();
            $stamps = StampCustomer::where("customer_id", $customer->id)->whereNull("consumed_at")
                ->where('expired_at', '>=', $now)
                ->orderBy("expired_at")->limit($spend_stamp_num)->get();

            if ($stamps->count() < $spend_stamp_num) {
                throw new ErrException("集章不足");
            }

            $souce_name= StoreEmployee::where('uid',$data['uid'])->pluck('name')[0];
            $created_at=date('Y-m-d H:i:s');
            foreach ($stamps as $stamp) {
                $stamp->consumed_at = $created_at;
                $stamp->source=$souce_name;
                $stamp->type=StampCustomerType::ALREADY_USE;
                $stamp->save();
            }
        });
    }

    public function pointExchange($data)
    {
        $created_at = date('Y-m-d H:i:s');
        $expired_at = date('Y-m-d H:i:s', strtotime("+1 year", strtotime($created_at)));
        $stamp = new StampCustomer();
        $stamp->customer_id = $data['customer_id'];
        $stamp->type = StampCustomerType::POINTSEXCHANGE;
        $stamp->value = 1;
        $stamp->created_at = $created_at;
        $stamp->reference_type = '系統發放';
        $stamp->expired_at = $expired_at;
        $stamp->source = '點數兌換集章';
        $stamp->save();
    }
}
