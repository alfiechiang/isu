<?php

namespace Database\Seeders;

use App\Models\County;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        County::truncate();
        $insertData = [
            ['name' => 'Keelung_City', 'cn_name' => '基隆市' ,'region'=>'north'],
            ['name' => 'Taipei_City', 'cn_name' => '台北市','region'=>'north'],
            ['name' => 'New_Taipei_City', 'cn_name' => '新北市','region'=>'north'],
            ['name' => 'Taoyuan_City', 'cn_name' => '桃園市','region'=>'north'],
            ['name' => 'Hsinchu_City', 'cn_name' => '新竹市','region'=>'north'],
            ['name' => 'Hsinchu_County', 'cn_name' => '新竹縣','region'=>'north'],
            ['name' => 'Miaoli_County', 'cn_name' => '苗栗縣','region'=>'north'],
            ['name' => 'Taichung_City', 'cn_name' => '台中市','region'=>'middle'],
            ['name' => 'Changhua_County', 'cn_name' => '彰化縣','region'=>'middle'],
            ['name' => 'Nantou_County', 'cn_name' => '南投縣','region'=>'middle'],
            ['name' => 'Yunlin_County', 'cn_name' => '雲林縣','region'=>'middle'],
            ['name' => 'Chiayi_City', 'cn_name' => '嘉義市','region'=>'south'],
            ['name' => 'Chiayi_County', 'cn_name' => '嘉義縣','region'=>'south'],
            ['name' => 'Tainan_City', 'cn_name' => '台南市','region'=>'south'],
            ['name' => 'Kaohsiung_City', 'cn_name' => '高雄市','region'=>'south'],
            ['name' => 'Pingtung_County', 'cn_name' => '屏東縣','region'=>'south'],
            ['name' => 'Yilan_County', 'cn_name' => '宜蘭縣','region'=>'out_island'],
            ['name' => 'Hualien_County', 'cn_name' => '花蓮縣','region'=>'out_island'],
            ['name' => 'Taitung_County', 'cn_name' => '台東縣','region'=>'out_island'],
            ['name' => 'Kinmen_County', 'cn_name' => '金門縣','region'=>'out_island'],
            ['name' => 'Penghu_County', 'cn_name' => '澎湖縣','region'=>'out_island'],
            ['name' => 'Lienchiang_County', 'cn_name' => '連江縣','region'=>'out_island'],
            ['name' => 'Oversea', 'cn_name' => '海外','region'=>'oversea'],
        ];
        DB::table('counties')->insert($insertData);
    }
}
