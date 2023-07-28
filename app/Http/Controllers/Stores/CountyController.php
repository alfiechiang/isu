<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\CountyService;
use Exception;

class CountyController extends Controller
{

    protected CountyService $countyService;

    public function __construct(CountyService $countyService)
    {
        $this->countyService=$countyService;
    }

    public function list(){
        try {
            $res =  $this->countyService->list();;
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

}