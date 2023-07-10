<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\StoreEmployeeService;
use Exception;
use Illuminate\Http\Request;

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
}
