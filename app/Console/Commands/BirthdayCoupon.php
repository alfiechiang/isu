<?php

namespace App\Console\Commands;

use App\Models\CouponCustomer;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class BirthdayCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday-coupon:caculate {created_at?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $birthdayTimeString ="+60 days";

    protected $expireTimeString="+30 days";


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('exec birthday-coupon:caculate');
        $date = $this->argument('created_at');
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        $this->exec($date);
        //檢查前幾天的schedule 是否有發放
        for($i=1;$i<=3;$i++){
            $checkDateString ="-$i days";
            $checkDate = date('Y-m-d 23:59:59', strtotime($checkDateString, strtotime(date('Y-m-d'))));
            $this->exec($checkDate);
        }

       
    }

    private function exec($date){

        $birthday = date('m-d', strtotime($this->birthdayTimeString, strtotime($date)));
        $expire_at = date('Y-m-d 23:59:59', strtotime($this->expireTimeString, strtotime(date('Y-m-d'))));
        $customers = Customer::where('birthday', 'LIKE', '%' . $birthday . '%')->get();
        $customer_ids = $customers->pluck('id');
        $year_start_date = now()->firstOfYear()->format('Y-m-d 00:00:00');
        $year_end_date = now()->endOfYear()->format('Y-m-d 23:59:59');
        $diff2 = CouponCustomer::whereIn('customer_id', $customer_ids)->whereBetween('created_at', [$year_start_date, $year_end_date])
            ->where('coupon_id', config('coupon.birthday.coupon_id'))->pluck('customer_id');
        $diff1 = collect($customer_ids);
        $birth_coupon_customers = $diff1->diff($diff2);
        $insertData=[];
        foreach($birth_coupon_customers as $birth_coupon_customer){
            $data=[
                'id'=>Str::uuid(),
                'code_script'=>'B'.date('Ymd'),
                'created_at'=>date('Y-m-d H:i:s'),
                'expired_at'=>$expire_at ,
                'status'=>1,
                'coupon_cn_name'=>'生日大禮',
                'customer_id'=>$birth_coupon_customer,
                'coupon_id'=>config('coupon.birthday.coupon_id')
            ];
            $insertData[]=$data;
        }
        DB::table('coupon_customers')->insert($insertData);

    }
}
