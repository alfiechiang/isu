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
            ['name' => 'Keelung_City', 'cn_name' => '基隆市'],
            ['name' => 'Taipei_City', 'cn_name' => '台北市'],
            ['name' => 'New_Taipei_City', 'cn_name' => '新北市'],
            ['name' => 'Taoyuan_City', 'cn_name' => '桃園市'],
            ['name' => 'Hsinchu_City', 'cn_name' => '新竹市'],
            ['name' => 'Hsinchu_County', 'cn_name' => '新竹縣'],
            ['name' => 'Miaoli_County', 'cn_name' => '苗栗縣'],
            ['name' => 'Taichung_City', 'cn_name' => '台中市'],
            ['name' => 'Changhua_County', 'cn_name' => '彰化縣'],
            ['name' => 'Nantou_County', 'cn_name' => '南投縣'],
            ['name' => 'Yunlin_County', 'cn_name' => '雲林縣'],
            ['name' => 'Chiayi_City', 'cn_name' => '嘉義市'],
            ['name' => 'Chiayi_County', 'cn_name' => '嘉義縣'],
            ['name' => 'Tainan_City', 'cn_name' => '台南市'],
            ['name' => 'Kaohsiung_City', 'cn_name' => '高雄市'],
            ['name' => 'Pingtung_County', 'cn_name' => '屏東縣'],
            ['name' => 'Yilan_County', 'cn_name' => '宜蘭縣'],
            ['name' => 'Hualien_County', 'cn_name' => '花蓮縣'],
            ['name' => 'Taitung_County', 'cn_name' => '台東縣'],
            ['name' => 'Kinmen_County', 'cn_name' => '金門縣'],
            ['name' => 'Penghu_County', 'cn_name' => '澎湖縣'],
            ['name' => 'Lienchiang_County', 'cn_name' => '連江縣'],
        ];
        DB::table('counties')->insert($insertData);
    }
}
