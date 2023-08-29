<?php

namespace App\Http\Controllers\Customers;

use App\Services\Customers\CouponService;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Services\Customers\HotelService;

class HotelController extends Controller
{

    protected HotelService $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }
    public function list(Request $request)
    {
        $res = $this->hotelService->pageList($request->all());
        return Response::format(200, $res, '請求成功');
    }

    public function hallList(Request $request)
    {
        $res = $this->hotelService->hallList($request->all());
        return Response::format(200, $res, '請求成功');
    }


    public function stronghold(Request $request)
    {
        $res = $this->hotelService->stronghold();
        return Response::format(200, $res, '請求成功');
    }
}
