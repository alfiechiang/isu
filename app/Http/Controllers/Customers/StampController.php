<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\Customers\PointResource;
use App\Http\Resources\Customers\StampResource;
use App\Models\Store;
use App\Point\PointEnums;
use App\Point\PointService;
use App\Services\CustomerRole\AuthService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class StampController extends Controller
{
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 取得集章資料.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Customers\StampResource
     * @apiResourceModel App\Models\StampCustomer paginate=10
     */
    public function index(): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $authUser = $this->authService->user();

        $models = $authUser->stampCustomer()->with(['reference', 'store'])->orderBy($defaultOrderColumn, $defaultOrderBy);
        $models = $models->paginate(self::PAGER);

        return StampResource::collection($models);
    }
}
