<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\ExchangeCouponService;
use Exception;
use Illuminate\Http\Request;

class ExchangeCouponController extends Controller
{

    protected $exchangeCouponService;

    public function __construct(ExchangeCouponService $exchangeCouponService)
    {
        $this->exchangeCouponService = $exchangeCouponService;
    }

    public function exchange(Request $request)
    {
        try {
            $this->exchangeCouponService->exchange($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    
  
    
  
}
