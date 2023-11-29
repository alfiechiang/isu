<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\StoreEmployeeService;
use App\Services\Stores\OperatorLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreEmployeeController extends Controller
{

    protected StoreEmployeeService  $storeEmployeeService;

    protected OperatorLogService $operatorLogService;


    public function __invoke()
    {
        // Controller logic here
    }

    public function __construct(StoreEmployeeService $storeEmployeeService,OperatorLogService $operatorLogService)
    {
        $this->storeEmployeeService = $storeEmployeeService;
        $this->operatorLogService=$operatorLogService;
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
            DB::transaction(function () use ($request, $uid) {
                $this->storeEmployeeService->update($uid, $request->all());
                $data= $request->all();
                $data['type']='update';
                $this->operatorLogService->createStoreEmployeeLog('account_mamage',$uid,$data);
            });
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
