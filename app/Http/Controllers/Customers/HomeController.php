<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Services\Customers\HomeServe;
use App\Services\Customers\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }
    public function list()
    {
        $res = $this->homeService->list();
        return Response::format(200, $res, '請求成功');
    }

}