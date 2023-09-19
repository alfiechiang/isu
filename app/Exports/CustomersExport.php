<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;



class CustomersExport implements FromCollection,WithHeadings,WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $customers = Customer::with('social_accounts')->get();

        $results=[];
        foreach($customers as $customer){
            $result=[];
            $result['guid']=$customer->guid;
            $result['name']=$customer->name;
            $result['avatar']=$customer->avatar;
            $result['country_code']=$customer->country_code;
            $result['phone']=$customer->phone;
            $result['email']=$customer->email;

            if($customer->gender=='male'){
                $result['gender']= '男';
            }else if($customer->gender=='female'){
                $result['gender']= '女';
            }else{
                $result['gender']=$customer->gender;
            }

            $result['birthday']=$customer->birthday;
            $result['interest']=$customer->interest;
            $result['bind_platform'] = $this->bind_platform($customer->social_accounts->toArray());
            $result['country']=$customer->country;
            $result['county']=$customer->county;
            $result['district']=$customer->district;
            $result['postal']=$customer->postal;
            $result['address']=$customer->address;
            $result['desc']=$customer->desc;
            $result['created_at']=$customer->created_at;
            $result['updated_at']=$customer->updated_at;
            $results[]=$result;
        }

        return collect($results);
    }


    private function bind_platform( array $socilal_accounts ){

        $platforms='';
        if(empty($socilal_accounts)){
            return $platforms;
        }

        foreach($socilal_accounts as $account){
            if($platforms==''){

                $platforms .= $account['provider_name'];
            }else{
                $platforms .= ',' . $account['provider_name'];
            }
        }

        return $platforms;
    }

    public function headings(): array
    {
        return [
            'GUID',
            '姓名',
            '照片路徑',
            '區碼',
            '手機號碼',
            '信箱',
            '性別',
            '生日',
            '興趣',
            '已綁定平台',
            '國家/地區',
            '縣市',
            '鄉鎮市區',
            '郵遞區號',
            '地址',
            '備註',
            '創建日期時間',
            '修改日期'
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
            'O' => 50,
            'P' => 35,
            'Q' => 20,
            'R' => 20,
        ];
    }
}
