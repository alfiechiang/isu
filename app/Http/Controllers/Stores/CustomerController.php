<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\CustomerService;
use App\Services\Stores\OperatorLogService;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    protected CustomerService $customerService;



    public function __construct(CustomerService $customerService,OperatorLogService $operatorLogService)
    {
        $this->customerService = $customerService;
    }

    public function list(Request $request)
    {
        try {
            $res = $this->customerService->list($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findone($guid)
    {
        try {
            $res = $this->customerService->findone($guid);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function update(Request $request, $guid)
    {
        try {
                $this->customerService->update($request->all(), $guid);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function social(Request $request)
    {
        try {
            $res = $this->customerService->socialaccounts($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }
        
}
