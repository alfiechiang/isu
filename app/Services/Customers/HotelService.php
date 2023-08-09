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

   public function stronghold()
   {

      return Hotel::select('county', DB::raw('count(county) as total'))->groupBy('county')
         ->get();
   }
}
