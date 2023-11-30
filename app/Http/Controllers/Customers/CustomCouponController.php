<?php

namespace App\Http\Controllers\Customers;

use App\Services\Customers\CouponService;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Services\Customers\CustomCouponService;

class CustomCouponController extends Controller
{
    
    protected CustomCouponService $customCouponService;

    public function __construct(CustomCouponService $customCouponService)
    {
        $this->customCouponService = $customCouponService;
    }
    public function customerList(Request $request){
       $res=$this->customCouponService->customerPageList($request->all());

       return Response::format(200,$res,'請求成功');
    }

   
}
