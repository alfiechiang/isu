<?php

namespace App\Console\Commands;

use App\Models\CouponCustomer;
use Illuminate\Console\Command;

class UpdateBirthdayCouponExpireAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-birthday-coupon-expire-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
       $coupons=CouponCustomer::where('coupon_id','B20230707')->with('customer')->get();
       $coupons->map(function($item){
            if(isset($item->customer)){
                if(isset($item->customer->birthday)){
                    $birthday=$item->customer->birthday;
                    $birthday = date('Y') . '-' . date('m-d', strtotime($birthday));
                    $expired_at = date('Y-m-d H:i:s', strtotime("+30 days", strtotime($birthday)));
                    $item->expired_at=$expired_at;
                    $item->save();
                }              
            }
       });

    }
}
