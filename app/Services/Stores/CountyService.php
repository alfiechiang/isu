<?php

namespace App\Services\Stores;

use App\Models\County;

class CountyService
{
    public function list(){
        return County::all();
    }

}
