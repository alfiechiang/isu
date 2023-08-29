<?php

namespace App\Services\Customers;

use App\Models\County;

class CountyService
{

   public function list()
   {
      return County::all();
   }


}
