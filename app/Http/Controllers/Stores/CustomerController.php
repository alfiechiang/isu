<?php

namespace App\Http\Controllers\Stores;

use App\Exports\CustomersExport;
use App\Http\Response;
use App\Models\Customer;
use App\Services\Stores\CustomerService;
use App\Services\Stores\OperatorLogService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{

    protected CustomerService $customerService;

    protected  OperatorLogService $operatorLogService;

    public function __construct(CustomerService $customerService, OperatorLogService $operatorLogService)
    {
        $this->customerService = $customerService;
        $this->operatorLogService = $operatorLogService;
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
            $data = $request->all();
            
            $data['type'] = 'update';

            $this->operatorLogService->createCustomerLog('customer_manage', $guid, $data);
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

    public function export(Request $request){
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    public function import(Request $request){
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
