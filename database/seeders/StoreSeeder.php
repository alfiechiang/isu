<?php

namespace Database\Seeders;

use App\Coupon\CouponEnums;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = \App\Models\Store::factory(['name' => '愛嬉遊分館', 'tax_id' => '85283492'])->create();

        \App\Models\StoreEmployee::factory()
            ->count(1)
            ->for($store)
            ->create();
    }
}
