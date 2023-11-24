<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\CouponDisableLogService;
use Exception;
use Illuminate\Http\Request;

class CouponDisableLogController extends Controller
{

    protected CouponDisableLogService $couponDisableLogService;

    public function __construct(CouponDisableLogService $couponDisableLogService)
    {

        $this->couponDisableLogService = $couponDisableLogService;
    }

    public function pageList(Request $request)
    {
        try {
            $res=$this->couponDisableLogService->customerSpecificCouponPageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }


}
