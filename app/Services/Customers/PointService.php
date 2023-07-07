<?php

namespace App\Services\Customers;

use App\Models\PointCustomer;
use Illuminate\Support\Facades\Auth;

class PointService
{

    public function list()
    {

        $auth = Auth::user();
        return PointCustomer::where('customer_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function pageList($data)
    {
        $auth = Auth::user();
        return PointCustomer::where('customer_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->paginate($data['per_page']);
    }
}
