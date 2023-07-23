<?php

namespace Database\Seeders;

use App\Models\StorePrivilegeIntermediate;
use App\Models\StorePrivilegeMenu;
use App\Models\StorePrivilegeRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorePrivilegeIntermediateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StorePrivilegeIntermediate::truncate();

        $insertData=[];
        //最高權限
        $top =StorePrivilegeRole::where('name','TOP')->first();
        $top_id=$top->id;
        $menu_cn_names=[
            '帳號管理','會員管理','集章管理','集章品項管理','點數管理',
            '優惠卷管理','訂房去','玩家帶路','最新消息',
            '推薦店家','商城'
        ];
        $menu_ids =StorePrivilegeMenu::whereIn('cn_name',$menu_cn_names)->pluck('id');
        foreach( $menu_ids as $menu_id){
            $insertData[]=['role_id'=> $top_id,'menu_id'=>$menu_id];
        }

        $store =StorePrivilegeRole::where('name','STORE')->first();
        $store_id=$store->id;
        $menu_cn_names=[
            '會員管理','集章管理','集章品項管理','點數管理',
        ];
        $menu_ids =StorePrivilegeMenu::whereIn('cn_name',$menu_cn_names)->pluck('id');
        foreach( $menu_ids as $menu_id){
            $insertData[]=['role_id'=> $store_id,'menu_id'=>$menu_id];
        }

        $counter =StorePrivilegeRole::where('name','COUNTER')->first();
        $counter_id=$counter->id;
        $menu_cn_names=[
            '會員管理','集章管理','點數管理','兌換優惠卷','玩家帶路',
        ];
        $menu_ids =StorePrivilegeMenu::whereIn('cn_name',$menu_cn_names)->pluck('id');
        foreach( $menu_ids as $menu_id){
            $insertData[]=['role_id'=> $counter_id,'menu_id'=>$menu_id];
        }
        StorePrivilegeIntermediate::insert($insertData);

    }
}
