<?php

namespace App\Http\Controllers\Stores;

use App\Coupon\CouponService;
use App\Enums\StatusCode;
use App\Http\Resources\Stores\CouponCustomerCollection;
use App\Http\Resources\Stores\CouponCustomerResource;
use App\Models\CouponCode;
use App\Models\Customer;
use App\Models\StoreEmployee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CouponCustomerController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * 取得顧客優惠券資料.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @param string $customer_id
     * @return AnonymousResourceCollection|Response
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Stores\CouponCustomerResource
     * @apiResourceModel App\Models\CouponCustomer paginate=10
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

            $models = $customer->coupons()->with(['operator', 'reference', 'coupon', 'coupon_code', 'redeem', 'redeem.operator', 'redeem.reference'])->orderBy($defaultOrderColumn, $defaultOrderBy);
            $models = $models->paginate(self::PAGER);

            return CouponCustomerResource::collection($models);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 取得顧客優惠券訊息.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Stores\CouponCustomerResource
     * @apiResourceModel App\Models\CouponCustomer
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到優惠券.",
     *   "code": 1002
     * }
     * @response status=400 scenario="未綁定任何顧客" {
     *   "errors": "該優惠券未綁定任何顧客.",
     *   "code": 100401
     * }
     *
     * @param string $coupon_code
     * @return CouponCustomerResource|Response
     */
    public function show(string $coupon_code): CouponCustomerResource|Response
    {
        try {
            $couponCode = CouponCode::where('code', $coupon_code)->first();

            if (!$couponCode) {
                throw new Exception('找不到優惠券.', StatusCode::MODEL_NOT_EXIST->value);
            }

            if (!$couponCode->code_customer) {
                throw new Exception('該優惠券未綁定任何顧客.', StatusCode::COUPON_UNBOUND_USER->value);
            }

            $codeCustomer = $couponCode->code_customer;
            $codeCustomer->load(['operator', 'reference', 'customer', 'coupon', 'coupon_code', 'redeem', 'redeem.operator', 'redeem.reference']);

            return new CouponCustomerResource($codeCustomer);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 兌換顧客優惠券.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @bodyParam memo string 備註
     *
     * @response scenario=success
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到優惠券.",
     *   "code": 1002
     * }
     * @response status=400 scenario="未綁定任何顧客" {
     *   "errors": "該優惠券未綁定任何顧客.",
     *   "code": 100401
     * }
     * @response status=400 scenario="已經過期" {
     *   "errors": "該優惠券已經過期.",
     *   "code": 100402
     * }
     * @response status=400 scenario="已經使用" {
     *   "errors": "該優惠券已經使用.",
     *   "code": 100403
     * }
     */
    public function redeem(Request $request, string $coupon_code): CouponCustomerResource|Response
    {
        try {
            $this->couponService->redeemCoupon($coupon_code, $request->get('memo'));

            // 返回成功響應
             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 發送顧客優惠券.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @bodyParam customer_id string required 顧客 id
     * @bodyParam type string required 優惠券類型必須是 special, open_card. Example: special
     * @bodyParam memo string 備註
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
    public function send(Request $request): CouponCustomerResource|Response
    {
        $data = $request->only('customer_id', 'type', 'memo');

        try {
            // 驗證輸入數據
            $validator = Validator::make($data, [
                'customer_id' => 'required',
                'type' => 'required|in:open_card,special',
            ]);

            if ($validator->fails()) {
                // 如果驗證失敗，拋出一個異常
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $customer = Customer::find($data['customer_id']);
            if(!$customer) {
                throw new Exception("找不到顧客資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $reference = StoreEmployee::first();

            $this->couponService->generateCouponsForCustomer($data['type'], $customer, $data['memo'], $reference->store);

             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
