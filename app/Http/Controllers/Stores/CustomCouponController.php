<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Exports\CustomCouponCustomersExport;
use App\Imports\CustomerCouponPelopeListImport;
use App\Models\CustomCoupon;
use App\Services\Stores\CountyService;
use App\Services\Stores\CustomCouponService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class CustomCouponController extends Controller
{

    protected CustomCouponService $customCouponService;

    public function __construct(CustomCouponService $customCouponService)
    {
        $this->customCouponService = $customCouponService;
    }

    public function create(Request $request)
    {

        try {
            $res=$this->customCouponService->create($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findone($coupon_code)
    {

        try {
            $res = $this->customCouponService->findone($coupon_code);
            return Response::format(200,  $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function update(Request $request, $coupon_code)
    {
        try {
            $data = $request->all();
            $res=$this->customCouponService->update($coupon_code, $data);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function send($coupon_code) //發放測試用
    {
        try {
            $this->customCouponService->send($coupon_code);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function import(Request $request)
    {

        try {

            $coupon_id = $request->coupon_id;

            $coupon = CustomCoupon::where('code', $coupon_id)->first();
            Excel::import(new CustomerCouponPelopeListImport($coupon_id, $coupon->name), request()->file('import'));
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function export($coupon_code)
    {

        try {
            return Excel::download(new CustomCouponCustomersExport($coupon_code), 'custom_coupon.xlsx');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }



    public function findoneCouponDisable(Request $request,$coupon_code) //全數失效
    {
        try {
            $operaterIp=$request->ip();
            $this->customCouponService->findoneCouponDisable($coupon_code,$operaterIp);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function pageList(Request $request) 
    {
        try {
            $res=$this->customCouponService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

}
