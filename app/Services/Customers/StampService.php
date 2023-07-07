<?php

namespace App\Services\Customers;


use App\Models\StampCustomer;



class StampService
{

    public function list($data){

        $Builder = new  StampCustomer();
        $now = date('Y-m-d H:i:s');

        if ($data['serch'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
                ->where('expired_at', '>=', $now)->whereNull('consumed_at');
        }

        if ($data['serch'] == 'HAVE_USE_OR_EXPIRE') {
            $Builder = $Builder->where('expired_at', '<=', $now)->whereNotNull('consumed_at');;
        }

       return $Builder->get();

    }

    public function pageList($data) {

        $Builder = new  StampCustomer();
        $now = date('Y-m-d H:i:s');

        if ($data['search'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
            ->where('expired_at', '>=', $now)->whereNull('consumed_at')
            ->orderBy('expired_at', 'asc');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {
            $Builder = $Builder->where('expired_at', '<=', $now)->whereNotNull('consumed_at')
            ->orderBy('consumed_at', 'desc');
        }

       return $Builder->paginate($data['per_page']);
    }

}