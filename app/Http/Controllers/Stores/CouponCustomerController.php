<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\CouponService;
use Exception;
use Illuminate\Http\Request;

class CouponCustomerController extends Controller
{
    
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function pageList(Request $request){
        try {
            $res =  $this->couponService->pageList($request->all());
            
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findoneCouponByMember($coupon_code,$coupon_id){
        try {
            $res =  $this->couponService->findoneCouponByMember($coupon_code,$coupon_id);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
     }

    public function peoplePageList(Request $request,$coupon_code){
        try {
            $res =  $this->couponService->peoplePageList($request->all(),$coupon_code);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

   
    
}
