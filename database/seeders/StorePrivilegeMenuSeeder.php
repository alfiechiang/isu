<?php

namespace Database\Seeders;

use App\Models\StorePrivilegeMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorePrivilegeMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StorePrivilegeMenu::truncate();
        $insertData=[
            ['name'=>'account_mamage','cn_name'=>'帳號管理'],
            ['name'=>'customer_mamage','cn_name'=>'會員管理'],
            ['name'=>'stamp_mamage','cn_name'=>'集章管理'],
            ['name'=>'stamp_item_mamage','cn_name'=>'集章品項管理'],
            ['name'=>'point_mamage','cn_name'=>'點數管理'],
            ['name'=>'coupon_mamage','cn_name'=>'優惠卷管理'],
            ['name'=>'reserve_room','cn_name'=>'訂房去'],
            ['name'=>'follow_player','cn_name'=>'玩家帶路'],
            ['name'=>'latest_news','cn_name'=>'最新消息'],
            ['name'=>'recommend_store','cn_name'=>'推薦店家'],
            ['name'=>'mall','cn_name'=>'商城'],
        ];

        StorePrivilegeMenu::insert($insertData);
    }
}
