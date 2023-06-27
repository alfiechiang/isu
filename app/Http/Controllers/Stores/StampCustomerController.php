<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Http\Resources\Stores\StampCustomerResource;
use App\Models\Customer;
use App\Models\Store;
use App\Services\StoreRole\AuthService;
use App\Stamp\Enums\StampDistribution;
use App\Stamp\StampCardService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class StampCustomerController extends Controller
{
    protected StampCardService $stampService;
    protected AuthService $authService;

    public function __construct(StampCardService $stampService, AuthService $authService)
    {
        $this->stampService = $stampService;
        $this->authService = $authService;
    }

    /**
     * 取得顧客集章.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @param string $customer_id
     *
     * @return AnonymousResourceCollection|Response
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Stores\StampCustomerResource
     * @apiResourceModel App\Models\StampCustomer paginate=10
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到顧客資料.",
     *   "code": 1002
     * }
     */
    public function index(string $customer_id): AnonymousResourceCollection|Response
    {
        try {
            $defaultOrderColumn = 'created_at';
            $defaultOrderBy = 'desc';

            $customer = Customer::find($customer_id);

            if(!$customer) {
                throw new Exception("找不到顧客資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $models = $customer->stampCustomer()->with(['operator', 'reference', 'store'])->orderBy($defaultOrderColumn, $defaultOrderBy);
            $models = $models->paginate(self::PAGER);

            return StampCustomerResource::collection($models);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 調整顧客集章.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @bodyParam type string required 發送類型必須是: store, point Example: store
     * @bodyParam quantity int required 調整章數
     * @bodyParam expiredAt date 到期時間預設 1 年 Example: 2023-10-10
     * @bodyParam memo string 備註
     * @bodyParam customer_id string required 顧客 id
     * @bodyParam store_id string required 店家 id
     * @bodyParam consumedAt date 消費日期 Example: 2023-10-10
     *
     * @response scenario=success
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The customer_id field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到顧客資料.",
     *   "code": 1002
     * }
     */
    public function quantity(Request $request): Response
    {
        $data = $request->only('type', 'quantity', 'expiredAt', 'memo', 'customer_id', 'store_id', 'consumedAt');

        try {
            $validator = Validator::make($data, [
                'type' => 'required|in:store,point',
                'quantity' => 'required',
                'customer_id' => 'required',
                'store_id' => 'required',
                'expiredAt' => 'date_format:"Y-m-d"',
                'consumedAt' => 'date_format:"Y-m-d"',
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $authUser = $this->authService->user();
            $customer = Customer::find($data['customer_id']);
            if(!$customer) {
                throw new Exception("找不到顧客資料.", StatusCode::MODEL_NOT_EXIST->value);
            }
            $store = Store::find($data['store_id']);
            if(!$store) {
                throw new Exception("找不到店家資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $expiredAt = null;
            if(Arr::get($data, 'expiredAt') != '') $expiredAt = Carbon::createFromFormat("Y-m-d", $data['expiredAt']);

            $this->stampService->grantStampForCustomer(
                StampDistribution::tryFrom($data['type']),
                $customer,
                $data['quantity'],
                $expiredAt,
                $store,
                $authUser,
                $data['memo'] ?? null,
                data_get($data, 'consumedAt'),
            );

            return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
