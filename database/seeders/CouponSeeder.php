<?php

namespace Database\Seeders;

use App\Coupon\CouponEnums;
use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelTYPEs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coupons')->delete(); 
        $insertData =[
            ['id'=>'B20230707','content_desc'=>'生日禮卷','name'=>'生日禮卷'],
            ['id'=>'F20230707','content_desc'=>'好久不見','name'=>'好久不見'],
            ['id'=>'C20230707','content_desc'=>'開卡禮','name'=>'開卡禮'],
            ['id'=>'M20230707','content_desc'=>'會員大禮包','name'=>'會員大禮包'],
        ];

        Coupon::insert($insertData);
    }
}
