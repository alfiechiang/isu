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
            $coupon_code = $request->coupon_code;
            $coupon = CustomCoupon::where('code', $coupon_code)->first();
            Excel::import(new CustomerCouponPelopeListImport($coupon_code, $coupon->name), request()->file('import'));
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function export($coupon_code)
    {

        try {
            $customCoupon=CustomCoupon::where('code',$coupon_code)->first();
            $name=$customCoupon->name;
            return Excel::download(new CustomCouponCustomersExport($coupon_code), "$name.xlsx");
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }



    public function findoneCouponDisable(Request $request,$coupon_code) //全數失效
    {
        try {
            $operaterIp=$request->ip();
            $desc=$request->desc;
            $this->customCouponService->findoneCouponDisable($coupon_code,$operaterIp,$desc);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }


    public function findoneCouponDisableByMember(Request $request,$coupon_code) //全數失效
    {
        try {
            $operaterIp=$request->ip();
            $desc=$request->desc;
            $coupon_id=$request->coupon_id;
            $this->customCouponService->findoneCouponDisableByMember($coupon_code,$operaterIp,$desc,$coupon_id);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findoneCouponByMember(Request $request,$coupon_code) //全數失效
    {
        try {
            $coupon_id=$request->coupon_id;
            $res=$this->customCouponService->findoneCouponByMember($coupon_code,$coupon_id);
            return Response::format(200, $res, '請求成功');
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
