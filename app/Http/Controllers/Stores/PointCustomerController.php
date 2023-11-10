<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\PointCustomerService;
use Exception;
use Illuminate\Http\Request;

class PointCustomerController extends Controller
{

    protected  PointCustomerService $pointCustomerService;

    public function __construct(PointCustomerService $pointCustomerService)
    {
    
        $this->pointCustomerService=$pointCustomerService;
    }

    public function list(Request $request){
        try {
            $res=$this->pointCustomerService->list($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
        
            return Response::errorFormat($e);
        }
    }

    public function create(Request $request){
        try {
            $this->pointCustomerService->create($request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function delete(Request $request){
        try {
            $this->pointCustomerService->delete($request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function totalPoints(Request $request){
        try {
            $res=$this->pointCustomerService->totalPoints($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
        
            return Response::errorFormat($e);
        }
    }

    public function logList(Request $request){
        try {
            $res=$this->pointCustomerService->logPageList($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
        
            return Response::errorFormat($e);
        }
    }
    
}
