<?php

namespace App\Console\Commands;

use App\Models\CustomCoupon as ModelsCustomCoupon;
use App\Services\Stores\CustomCouponService;
use Illuminate\Console\Command;

class CustomCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-coupon';

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
        $starttime =date('Y-m-d 00:00:00');
        $endtime =date('Y-m-d 23:59:59');

        $coupons=ModelsCustomCoupon::whereBetween('issue_time',[$starttime,$endtime])->get();
        $service=new CustomCouponService();
        foreach($coupons as $coupon){
            $service->send($coupon->code);
        }
        
    }
}
