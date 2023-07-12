<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\StoreEmployeeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeController extends Controller
{

    protected StoreEmployeeService  $storeEmployeeService;

    public function __invoke()
    {
        // Controller logic here
    }

    public function __construct(StoreEmployeeService $storeEmployeeService)
    {
        $this->storeEmployeeService = $storeEmployeeService;
    }

    public function create(Request $request)
    {
        try {
            $this->storeEmployeeService->create($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function delete($uid){
        try {
            $this->storeEmployeeService->delete($uid);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

    }

    public function update(Request $request,$uid){
        try {
            $this->storeEmployeeService->update($uid,$request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

    }

    public function findOne($uid){
        try {
            $res=$this->storeEmployeeService->findOne($uid);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }

    }

    public function pageList(Request $request)
    {
        try {
            $res=$this->storeEmployeeService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

}
