<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\Customers\CouponCollection;
use App\Http\Resources\Customers\CouponResource;
use App\Services\CustomerRole\AuthService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CouponController extends Controller
{
    /**
     * The AuthService instance.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 取得優惠券.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Customers\CouponResource
     * @apiResourceModel App\Models\CouponCustomer paginate=10
     */
    public function index(): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $authUser = $this->authService->user();

        $models = $authUser->coupons()->with(['coupon', 'coupon_code', 'reference', 'redeem', 'redeem.reference'])->orderBy($defaultOrderColumn, $defaultOrderBy);
        $models = $models->paginate(self::PAGER);

        return CouponResource::collection($models);
    }
}
