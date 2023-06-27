<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\Customers\PointResource;
use App\Models\Store;
use App\Point\PointEnums;
use App\Point\PointService;
use App\Services\CustomerRole\AuthService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PointController extends Controller
{
    protected PointService $pointService;
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param PointService $pointService
     * @param AuthService $authService
     */
    public function __construct(PointService $pointService, AuthService $authService)
    {
        $this->authService = $authService;
        $this->pointService = $pointService;
    }

    /**
     * 取得點數歷程.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Customers\PointResource
     * @apiResourceModel App\Models\PointCustomer paginate=10
     */
    public function index(): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $authUser = $this->authService->user();

        $models = $authUser->points()->with(['reference'])->orderBy($defaultOrderColumn, $defaultOrderBy);
        $models = $models->paginate(self::PAGER);

        return PointResource::collection($models);
    }

    /**
     * 掃描商店獲得點數.
     *
     * @group Customers
     *
     * @response scenario=success
     * @response status=400 scenario="重複掃描相同店家" {
     *   "errors": "已經獲得過點數.",
     *   "code": 100301
     * }
     */
    public function scanStore($store_id): Response
    {
        try {
            $store = Store::findOrFail($store_id);

            $authUser = $this->authService->user();

            $this->pointService->createPoint(PointEnums::SOURCE_SCAN_STORE, $authUser, $store, $authUser);

             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
