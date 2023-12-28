<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Imports\RecommendsImport;
use App\Services\Customers\RecommendService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class RecommendController extends Controller
{
    protected RecommendService $recommendService;

    public function __construct(RecommendService $recommendService)
    {
        $this->recommendService = $recommendService;
    }

    public function list(Request $request)
    {
        try {
           $res= $this->recommendService->list($request->all());
            return Response::format(200,$res,'請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }


    public function import(Request $request){
        try {
            Excel::import(new RecommendsImport(), request()->file('import'));
            return Response::format(200, [], '請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }

    }

   
}
