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

    public function create(Request $request){
        try {
            $this->followPlayerService->create($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
      
    }

    public function update(Request $request, $follow_id){
        try {
            $this->followPlayerService->update($follow_id,$request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    
    }

    public function list(Request $request){
        try {
            $res=$this->followPlayerService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function delete( $hotel_id){
       
      
    }



  


}
