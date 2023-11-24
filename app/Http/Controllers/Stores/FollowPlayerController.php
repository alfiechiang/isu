<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\FollowPlayerService;
use Exception;
use Illuminate\Http\Request;

class FollowPlayerController extends Controller
{

    protected $followPlayerService;

    public function __construct(FollowPlayerService $followPlayerService)
    {

        $this->followPlayerService = $followPlayerService;
    }

    public function create(Request $request)
    {
        try {
            $this->followPlayerService->create($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function update(Request $request, $follow_id)
    {
        try {
            $data=$request->all();
            if(isset($data['creator'])){
                unset($data['creator']);
            }
            $this->followPlayerService->update($follow_id,$data);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function checkUpdatePermission( $follow_id)
    {
        try {
            $res=$this->followPlayerService->checkUpdatePermission($follow_id);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findone($follow_id)
    {
        try {
            $res = $this->followPlayerService->findone($follow_id);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function delete($follow_id)
    {
        try {
            $this->followPlayerService->delete($follow_id);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function list(Request $request)
    {
        try {
            $res = $this->followPlayerService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function ownList(Request $request)
    {
        try {
            $res = $this->followPlayerService->ownList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

  
}
