<?php

namespace App\Exports;

use App\Enums\StampCustomerType;
use App\Models\CustomCouponCustomer;
use App\Models\StampCustomer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StampCustomersExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $stamps = StampCustomer::with('customer')->with('presentguid')->get();
        $results = [];
        foreach ($stamps as $stamp) {
            $result = [];
            $result['guid'] = $stamp->customer->guid;
            $result['name'] = $stamp->customer->name;
            $result['created_at'] = $stamp->created_at;
            $result['expired_at'] = $stamp->expired_at;
            $result['source'] = $stamp->source;
            $type='';
            $exchange_time='';
            switch ($stamp->type) {
                case StampCustomerType::POINTSEXCHANGE->value:
                    $type='點數兌換';
                    break;
                case StampCustomerType::STAY->value:
                    $type='住宿獲取';
                    $exchange_time = $stamp->created_at;
                    break;
                case StampCustomerType::SYSTEM_SEND->value:
                    $type='系統發送';
                    break;
                case StampCustomerType::ALREADY_USE->value:
                    $type='已使用';
                    break;
                    case StampCustomerType::SOMEONE_DELIVER->value:
                    $type='他人贈送';
                    break;
                case StampCustomerType::HAVE_EXPIRE->value:
                    $type='已過期';
                    break;
                default:
            }
            $result['type'] = $type;
            $result['exchange_time'] = $exchange_time;
            if(StampCustomerType::STAY->value==$stamp->type){
                $result['exchange_store'] = $stamp->source;
            }
            //ALREADY_USE_OWN_DELIVER
            if(StampCustomerType::ALREADY_USE_OWN_DELIVER->value==$stamp->type){
                $result['exchange_store'] = $stamp->source;
            }

            $results[]=$result;
        }

        return collect($results);
    }

    public function headings(): array
    {
        return [
            '會員GUID',
            '會員名稱',
            '獲得日期/時間',
            '過期日期/時間',
            '來源',
            '類型',
            '兌換日期',
            '兌換店家'
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
            'H' => 50,
        ];
    }
}
