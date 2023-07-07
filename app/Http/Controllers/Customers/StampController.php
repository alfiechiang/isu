<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\Customers\PointResource;
use App\Http\Resources\Customers\StampResource;
use App\Http\Response;
use App\Models\Store;
use App\Point\PointEnums;
use App\Point\PointService;
use App\Services\CustomerRole\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        }

       return Response::format(200,$res,'請求成功');
    }
}
