<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\StampCustomerService;
use Exception;
use Illuminate\Http\Request;

class StampCustomerController extends Controller
{

    protected $stampCustomerService;

    public function __construct(StampCustomerService $stampCustomerService)
    {
        $this->stampCustomerService = $stampCustomerService;
    }

    public function create(Request $request)
    {
        try {

            $stamp_num = $request->get('stamp_num');
            for ($i = 0; $i < $stamp_num; $i++) {
                $data=$request->all();
                $data['operator_ip']=request()->ip();
                $this->stampCustomerService->create($data);
            }
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function list(Request $request)
    {
        try {
            $res = $this->stampCustomerService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function delete($stamp_id)
    {
        try {
            $operator_ip=request()->ip();

            $this->stampCustomerService->delete($stamp_id,$operator_ip);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function logList(Request $request)
    {
        try {
            $res = $this->stampCustomerService->logPageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }
}
