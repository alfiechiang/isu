<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\RecommendService;
use Exception;
use Illuminate\Http\Request;

class RecommendController extends Controller
{

    protected  RecommendService $recommendService;

    public function __construct(RecommendService $recommendService)
    {
        $this->recommendService=$recommendService;
    }

    public function list(Request $request){
        try {
            $res=$this->recommendService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function create(Request $request){
        try {
            $this->recommendService->create($request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function findone($recommend_id){
        try {
            $res=$this->recommendService->findone($recommend_id);
            return Response::format(200, $res, '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function update(Request $request,$recommend_id){
        try {
            $this->recommendService->update($recommend_id,$request->all());
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

    public function delete($recommend_id){
        try {
            $this->recommendService->delete($recommend_id);
            return Response::format(200, [], '請求成功');
        }catch(Exception $e){
            return Response::errorFormat($e);
        }
    }

}