<?php

namespace App\Services\Customers;

use App\Exceptions\ErrException;
use App\Models\Customer;
use App\Models\StampCustomer;
use Illuminate\Support\Facades\Auth;
use App\Enums\StampCustomerType;
use App\Models\CouponCustomer;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class HotelService
{

   public function list($data)
   {
      return Hotel::with('images')->get();
   }

   public function pageList($data)
   {
      $Builder =  Hotel::with('images');
      if (isset($data['county'])) {
         $Builder = $Builder->where('county', $data['county']);
      }
      return  $Builder->paginate($data['per_page']);
   }

   public function hallList()
   {
      return Hotel::with('images')->groupBy('county')->get();
   }


   public function stronghold()
   {

      return Hotel::select('county', DB::raw('count(county) as total'))->groupBy('county')
         ->get();
   }
}
