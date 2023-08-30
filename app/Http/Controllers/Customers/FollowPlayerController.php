<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Services\Customers\FollowPlayerService;
use Exception;
use Illuminate\Http\Request;

class FollowPlayerController extends Controller
{
    protected FollowPlayerService $followPlayerService;

    public function __construct(FollowPlayerService $followPlayerService)
    {
        $this->followPlayerService = $followPlayerService;
    }

    public function list(Request $request){
        try {
            $res=$this->followPlayerService->pageList($request->all());
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

        return Response::format(200, $res, '請求成功');
    }

    public function findone($follow_id){
        try {
            $res=$this->followPlayerService->findone($follow_id);
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

        return Response::format(200, $res, '請求成功');
    }

    public function stronghold(){
        try {
            $res=$this->followPlayerService->stronghold();
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

        return Response::format(200, $res, '請求成功');

    }

}