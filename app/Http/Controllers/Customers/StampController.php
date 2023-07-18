<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use Illuminate\Http\Request;
use App\Services\Customers\StampService;

class StampController extends Controller
{

    protected StampService $stampService;

    public function __construct(StampService $stampService)
    {
        $this->stampService = $stampService;
    }

    public function index(Request $request)
    {

        if(isset($request->page)){
            $res=$this->stampService->pageList($request->all()); 
        }else{
            $res=$this->stampService->list($request->all()); 

        }

       return Response::format(200,$res,'請求成功');
    }

    public function deliver(Request $request)
    {
        try {
            $this->stampService->deliver($request->all());
            return Response::format(200,[],'請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function exchangeStamp(Request $request){
        try {
            $this->stampService->exchangeStamp($request->all());
            return Response::format(200,[],'請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }

    }
}
