<?php

namespace App\Http\Controllers\Customers;

use App\Services\Customers\CouponService;
use Illuminate\Http\Request;
use App\Http\Response;


class CouponController extends Controller
{
    
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    public function index(Request $request){

        if(isset($request->page)){
            $res=$this->couponService->pageList($request->all()); 
        }else{
            $res=$this->couponService->list($request->all()); 

        }

       return Response::format(200,$res,'請求成功');
       
    }
}
