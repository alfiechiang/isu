<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\OperatorLogService;
use Exception;
use Illuminate\Http\Request;

class OperatorLogController extends Controller
{

    protected $operatorLogService;

    public function __construct(OperatorLogService $operatorLogService)
    {

        $this->operatorLogService = $operatorLogService;
    }

    public function findLatestOne(Request $request){
        try {
            $res=$this->operatorLogService->findLatestOne($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

}