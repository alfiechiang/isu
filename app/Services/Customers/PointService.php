<?php

namespace App\Services\Customers;

use App\Enums\PotintCustomerTye;
use App\Exceptions\ErrException;
use App\Models\PointCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\Stamp;
use App\Models\Customer;

class PointService
{

    protected StampService $stampService;


    public function __construct(StampService $stampService)
    {
        $this->stampService = $stampService;
    }

    public function list()
    {

        $auth = Auth::user();
        return PointCustomer::where('customer_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function pageList($data)
    {
        $auth = Auth::user();
        $Builder =PointCustomer::where('customer_id', $auth->id);
        if(isset($data['sort'])){
            switch ($data['sort']){
                case 1:
                    $Builder=$Builder->orderBy('created_at', 'desc');
                    break;
                case 2:
                    $Builder=$Builder->orderBy('created_at', 'asc');
                    break;
            }
        }

        return $Builder->paginate($data['per_page']);
    }

    public function totalPoints($data){
        $auth=Auth::user();
        $customer_id = $auth->id;
        $customer=Customer::where('id',$customer_id)->first();
        return ['total'=>$customer->point_balance];
    }


    public function exchangeToStamps($data)
    {
        //類型 1:兌換集章2:進店掃描3:消費認證4:系統新增
        DB::transaction(function () use ($data) {
            $auth = Auth::user();
            $customer_id = $auth->id;
            $stamps_num =intval( $data['exchangePoints'] / Stamp::ONESTAMPVALUEPOINTS->value);
            $point_val=($stamps_num*Stamp::ONESTAMPVALUEPOINTS->value);
            $customer=Customer::where('id',$customer_id)->first();

            if($customer->point_balance<$point_val){
                throw new ErrException('兌換點數超過現有點數');
            }
            
            if($stamps_num==0){
                throw new ErrException('兌換點數小於1000點');
            }

            $customer->point_balance-=$point_val;
            $customer->save();

            PointCustomer::create([
                'source'=>'系統',
                'type'=>PotintCustomerTye::EXCHANGE_STAMP->value,
                'value'=>-$point_val,
                'customer_id'=>$customer_id
            ]);

            for ($i = 1; $i <= $stamps_num; $i++) {
                $this->stampService->pointExchange(['customer_id' => $customer_id]);
            }

        });
    }
}
