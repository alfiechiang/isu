<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\Customers\PointResource;
use App\Http\Response;
use App\Models\Store;
use App\Point\PointEnums;
use App\Services\Customers\PointService;
use App\Services\CustomerRole\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PointController extends Controller
{
    protected PointService $pointService;
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param PointService $pointService
     * @param AuthService $authService
     */
    public function __construct(PointService $pointService, AuthService $authService)
    {
        $this->authService = $authService;
        $this->pointService = $pointService;
    }

    public function index(Request $request)
    {    
        if(isset($request->page)){
            $res=$this->pointService->pageList($request->all()); 
        }else{
            $res=$this->pointService->list(); 
        }
        
       return Response::format(200,$res,'請求成功');
    }
    
    public function totalPoints(Request $request)
    {
        try {
            $res=$this->pointService->totalPoints($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }


    public function exchangeToStamps(Request $request)
    {

        try {
            $this->pointService->exchangeToStamps($request->all());
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
        return Response::format(200, [], '請求成功');
    }


    public function scanStore($store_id)
    {
        try {
            $store = Store::findOrFail($store_id);

            $authUser = $this->authService->user();

            //$this->pointService->createPoint(PointEnums::SOURCE_SCAN_STORE, $authUser, $store, $authUser);

             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
