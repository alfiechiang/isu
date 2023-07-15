<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\PrizeService;
use Exception;
use Illuminate\Http\Request;

class PrizeController extends Controller
{

    protected  PrizeService $prizeService;

    public function __construct(PrizeService $prizeService)
    {
        $this->prizeService=$prizeService;
    }

    public function list(Request $request){
        try {
            $res=$this->prizeService->list($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function create(Request $request){
        try {
            $this->prizeService->create($request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function findone($prize_id){
        try {
            $res=$this->prizeService->findone($prize_id);
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function update(Request $request,$prize_id){
        try {
            $this->prizeService->update($prize_id,$request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

}