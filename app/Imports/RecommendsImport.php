<?php

namespace App\Imports;

use App\Models\CustomCouponPeopleList;
use App\Models\Recommend;
use Carbon\Exceptions\EndLessPeriodException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class RecommendsImport implements ToCollection
{


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        unset($rows[0]);
        //遊樂園區/觀光工廠/博物館  1
        //銀行/郵局 2
        //公家機關  3
        //教育機構 4
        //伴手禮 5
        //旅宿 6
        //警消 7
        //交通  8
        //醫療 9
        //體驗 10
        //景點  11
        //餐飲  12
        //生活  13
        //購物  14
       // Recommend::truncate();
        $insertData = [];
        foreach ($rows as $row) {
            $item = [];
            $item['type'] =$this->switchToType($row[2]);
            $item['name']=$row[3];
            if(isset($row[4])){
                $item['content']=$row[4];
            }else{
                $item['content']=null;
            }
            $phone=null;
            if(isset($row[5])){
                $phone=$row[5];
            }
            $item['phone']=$phone;
            $cell_phone=null;
            if(isset($row[6])){
                $cell_phone=$row[6];
            }
            $item['cell_phone']=$cell_phone;
            $address=null;
            if(isset($row[7])){
                $address=$row[7];
            }
            $item['address']=$address;
            $official_website=null;
            if(isset($row[21])){
                $official_website=$row[21];
            }
            $item['official_website']=$official_website;
            $open_start_time=null;
            if(isset($row[16])){
                $timeValue = $row[16]; // 假設時間在第一列
                $dateTime = Date::excelToDateTimeObject($timeValue); // 將 Excel 時間轉換為 DateTime 對象                
                $open_start_time=$dateTime->format('H:i');
            }
            $item['open_start_time']=$open_start_time;
            $open_end_time=null;
            if(isset($row[17])){
                $timeValue = $row[17]; // 假設時間在第一列
                $dateTime = Date::excelToDateTimeObject($timeValue); // 將 Excel 時間轉換為 DateTime 對象                
                $open_end_time=$dateTime->format('H:i');
            }
            $item['open_end_time']=$open_end_time;
            $precision='';
            if(isset($row[18])){
                $precision=$row[18];
            }
            $item['precision']=$precision;
            $latitude='';
            if(isset($row[19])){
                $latitude=$row[19];
            }
            $item['latitude']=$latitude;

            $insertData[]=$item;
        }
        DB::table('recommends')->insert($insertData);
    }

    private function switchToType($str)
    {
        $type = 0;
        switch ($str) {
            case '遊樂園區/觀光工廠/博物館':
                $type = 1;
                break;
            case '銀行/郵局':
                $type = 2;
                break;
            case '公家機關':
                $type = 3;
                break;
            case '教育機構':
                $type = 4;
                break;
            case '伴手禮':
                $type = 5;
                break;
            case '旅宿':
                $type = 6;
                break;
            case '警消':
                $type = 7;
                break;
            case '交通':
                $type = 8;
                break;
            case '醫療':
                $type = 9;
                break;
            case '體驗':
                $type = 10;
                break;
            case '景點':
                $type = 11;
                break;
            case '餐飲':
                $type = 12;
                break;
            case '生活':
                $type = 13;
                break;
            case '購物':
                $type = 14;
                break;
        }

        return $type;
    }
}
