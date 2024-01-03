<?php

namespace App\Exports;

use App\Enums\PotintCustomerTye;
use App\Enums\StampCustomerType;
use App\Models\CustomCouponCustomer;
use App\Models\PointCustomer;
use App\Models\StampCustomer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PointCustomersExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $points = PointCustomer::with('customer')->get();
        $results = [];

        foreach ($points as $point) {
            $result = [];
            $result['customer_name'] = $point->customer->name;
            $result['created_at'] = $point->created_at;
            $result['source'] = $point->source;
            $type = '';
            switch ($point->type) {
                case PotintCustomerTye::EXCHANGE_STAMP->value:
                    $type = '兌換集章';
                    break;
                case PotintCustomerTye::STORE_SCAN->value:
                    $type = '店家掃描';
                    break;
                case PotintCustomerTye::CONSUME->value:
                    $type = '消費認證';
                    break;
                case PotintCustomerTye::SYSTEM_CREATE->value:
                    $type = '系統新增';
                    break;
                default:

            }
            $result['type']=$type;
            $point_num='';
            $minus_point_num='';
            if($point->value>0){
                $point_num=$point->value;
            }else{
                $minus_point_num=$point->value;
            }
            $result['point_num']=$point_num;
            $result['minus_point_num']=$minus_point_num;
            $exchange_time='';
            if($point->type ==PotintCustomerTye::EXCHANGE_STAMP->value){
                $exchange_time=$point->created_at;
            }
            $result['exchange_time']=$exchange_time;
            $results[]=$result;
        }
        return collect($results);
    }

    public function headings(): array
    {
        return [
            '會員名稱',
            '獲得日期/時間',
            '來源',
            '類型',
            '獲得數量',
            '扣除數量',
            '兌換日期/時間',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 35,
            'D' => 35,
            'E' => 20,
            'F' => 20,
            'G' => 30,
        ];
    }
}
