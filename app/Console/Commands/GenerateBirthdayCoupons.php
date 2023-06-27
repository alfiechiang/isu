<?php

namespace App\Console\Commands;

use App\Coupon\CouponEnums;
use App\Coupon\CouponFactory;
use App\Coupon\Generator\BirthdayCouponGenerator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateBirthdayCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected BirthdayCouponGenerator $couponGenerator;

    public function __construct(BirthdayCouponGenerator $generator)
    {
        parent::__construct();

        $this->couponGenerator = $generator;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        $this->couponGenerator->execution($today);
    }
}
