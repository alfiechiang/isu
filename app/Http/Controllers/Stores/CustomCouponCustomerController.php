<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Exports\CustomCouponCustomersExport;
use App\Imports\CustomerCouponPelopeListImport;
use App\Models\CustomCoupon;
use App\Services\Stores\CountyService;
use App\Services\Stores\CustomCouponCustomerService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class CustomCouponCustomerController extends Controller
{

    protected CustomCouponCustomerService $customCouponCustomerService;

    public function __construct(CustomCouponCustomerService $customCouponCustomerService)
    {
        $this->customCouponCustomerService = $customCouponCustomerService;
    }


    public function pageList(Request $request) 
    {
        try {
            $res=$this->customCouponCustomerService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

}