<?php

namespace Database\Seeders;

use App\Coupon\CouponEnums;
use Illuminate\Database\Console\Seeds\WithoutModelTYPEs;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Coupon::factory()->create([
            'name' => '生日禮券',
            'mode' => CouponEnums::MODE_DRAW,
            'type' => CouponEnums::TYPE_BIRTHDAY,
            'validity' => 60 * 24 * 30 * 3,
            'description' => null,
            'start_at' => null,
            'end_at' => null,
        ]);

        \App\Models\Coupon::factory()->create([
            'name' => '好久不見',
            'mode' => CouponEnums::MODE_DRAW,
            'type' => CouponEnums::TYPE_SLEEPING,
            'validity' => 60 * 24 * 30 * 6,
            'description' => null,
            'start_at' => null,
            'end_at' => null,
        ]);

        \App\Models\Coupon::factory()->create([
            'name' => '開卡禮',
            'mode' => CouponEnums::MODE_DRAW,
            'type' => CouponEnums::TYPE_OPEN_CARD,
            'validity' => -1,
            'description' => null,
            'start_at' => null,
            'end_at' => null,
        ]);

        \App\Models\Coupon::factory()->create([
            'name' => '會員大禮包',
            'mode' => CouponEnums::MODE_DRAW,
            'type' => CouponEnums::TYPE_INFORMATION_COMPLETE,
            'validity' => 60 * 24 * 30 * 12,
            'description' => null,
            'start_at' => null,
            'end_at' => null,
        ]);

        \App\Models\Coupon::factory()->create([
            'name' => '特別券',
            'mode' => CouponEnums::MODE_DRAW,
            'type' => CouponEnums::TYPE_SPECIAL,
            'validity' => -1,
            'description' => null,
            'start_at' => null,
            'end_at' => null,
        ]);
    }
}
