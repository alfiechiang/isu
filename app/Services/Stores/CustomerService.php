<?php

namespace App\Services\Stores;

use App\Models\Customer;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;

class CustomerService
{

    public function list($data){
       return  Customer::paginate($data['per_page']);
    }

    public function findone($guid){
       return  Customer::where('guid',$guid)->first();
    }

    public function update($data, $guid)
    {

        
        $customer =  Customer::where('guid', $guid)->first();
        $customer->fill($data);
        $customer->save();
    }

    public function socialaccounts($data){
        $customer =  Customer::where('guid', $data['guid'])->first();
        return SocialAccount::where('customer_id',$customer->id)->get();
    }



}