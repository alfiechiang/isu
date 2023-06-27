<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Http\Resources\Stores\PointCustomerResource;
use App\Models\Customer;
use App\Models\StoreEmployee;
use App\Point\PointEnums;
use App\Point\PointService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PointCustomerController extends Controller
{
    protected PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * 取得顧客點數歷程.
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
     * @apiResourceCollection App\Http\Resources\Stores\PointCustomerResource
     * @apiResourceModel App\Models\PointCustomer paginate=10
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

            $models = $customer->points()->with(['operator', 'reference'])->orderBy($defaultOrderColumn, $defaultOrderBy);
            $models = $models->paginate(self::PAGER);

            return PointCustomerResource::collection($models);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 設定顧客消費金額轉換點數.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @bodyParam amount int required 購買金額
     * @bodyParam customer_id string required 顧客 id
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
    public function amount(Request $request): Response
    {
        $data = $request->only('amount', 'customer_id');

        try {
            $validator = Validator::make($data, [
                'amount' => 'required',
                'customer_id' => 'required',
            ]);

            if ($validator->fails()) {
                // 如果驗證失敗，拋出一個異常
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $storeEmployee = StoreEmployee::first();
            $customer = Customer::find($data['customer_id']);
            if(!$customer) {
                throw new Exception("找不到顧客資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $reference = $storeEmployee->store;

            $this->pointService->createPoint(PointEnums::SOURCE_CONSUMPTION_INPUT, $customer, $reference, $storeEmployee, ['amount' => $data['amount']]);

             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
