<?php

namespace App\Exports;

use App\Models\CustomCouponCustomer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CustomCouponCustomersExport implements FromCollection,WithHeadings,WithColumnWidths
{


    protected $coupon_code;



    public function __construct($coupon_code)
    {
        $this->coupon_code = $coupon_code;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $customers =CustomCouponCustomer::with('customer')->where('coupon_code',$this->coupon_code)->get();

        $results=[];
        foreach($customers as $customer){
            $result=[];
            $result['coupon_code']=$customer->coupon_code;
            $result['coupon_name']=$customer->coupon_name;
            $result['guid']=$customer->guid;
            
            if(isset($customer->customer->name)){
                $result['customer_name']=$customer->customer->name;
            }

            if(isset($customer->customer->phone)){
                $result['phone']=$customer->customer->phone;
            }

            if(isset($customer->customer->email)){
                $result['email']=$customer->customer->email;
            }

            $result['exchange_time']=$customer->exchange_time;
            $result['exchange_place']=$customer->exchange_place;
            $result['exchanger']=$customer->exchanger;
            $results[]=$result;
        }

        return collect($results);
    }

    public function headings(): array
    {
        return [
            '優惠卷代碼',
            '優惠卷名稱',
            'GUID',
            '姓名',
            '手機號碼',
            '信箱',
            '兌換時間',
            '兌換地點',
            '兌換人員'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 35,
            'E' => 20,
            'F' => 20,
            'H' => 15,
            'I' => 50,
            'J' => 30,
            'O' => 30,
        ];
    }
}
