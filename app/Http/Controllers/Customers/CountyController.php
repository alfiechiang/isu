<?php

namespace App\Http\Controllers\Customers;

use App\Enums\CustomerCitizenship;
use App\Enums\CustomerStatus;
use App\Enums\StatusCode;

use App\Http\Requests\Customers\RegisterRequest;
use App\Http\Resources\Customers\CustomerResource;
use App\Rules\EmailOrPhone;
use App\Services\CustomerRole\AuthService;
use App\Services\CustomerRole\OtpService;
use App\Services\Customers\CountyService;
use Illuminate\Http\Request;
use App\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CountyController extends Controller
{
    
    /**
     * The AuthService instance.
     *
     * @var CountyService
     */
    protected CountyService $countyService;

    public function __construct(CountyService $countyService)
    {
        $this->countyService = $countyService;
    }

    public function list(){
        
        try {
            $res=$this->countyService->list();
            return Response::format(200,$res,'請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }

    
}
