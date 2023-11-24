<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Services\Customers\RecommendService;
use Illuminate\Http\Request;

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

   
}
