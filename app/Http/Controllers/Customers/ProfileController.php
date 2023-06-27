<?php

namespace App\Http\Controllers\Customers;

use App\Coupon\CouponEnums;
use App\Coupon\CouponService;
use App\Enums\CustomerCitizenship;
use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Resources\Customers\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerRole\AuthService;
use App\Services\CustomerRole\CustomerService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ProfileController extends Controller
{
    /**
     * The AuthService instance.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    protected CustomerService $customerService;

    protected CouponService $couponService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @param CustomerService $customerService
     * @param CouponService $couponService
     */
    public function __construct(AuthService $authService, CustomerService $customerService, CouponService $couponService)
    {
        $this->authService = $authService;
        $this->customerService = $customerService;
        $this->couponService = $couponService;
    }

    /**
     * 獲取已認證用戶的信息.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Customers\CustomerResource
     * @apiResourceModel App\Models\Customer
     *
     * @return CustomerResource|Response
     */
    public function index(): CustomerResource|Response
    {
        $authUser = $this->authService->user();

        return new CustomerResource($authUser);
    }

    /**
     * 更新用戶資料.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @bodyParam name string 姓名. Example: user
     * @bodyParam email string 電子郵件. Example: user@example.com
     * @bodyParam phone string 手機號碼. Example: 0912345678
     * @bodyParam gender string 性別必須是 male, female. Example: male
     * @bodyParam birthday string 生日. Example: 1990-10-10
     * @bodyParam address string 地址. Example: 台北市中正區
     * @bodyParam interest string 興趣. Example: 看書,打球
     * @bodyParam avatar file 用戶頭像.
     *
     * @apiResource App\Http\Resources\Customers\CustomerResource
     * @apiResourceModel App\Models\Customer
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The password field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="電子信箱已經被註冊" {
     *   "errors": "電子信箱已經被註冊.",
     *   "code": 100201
     * }
     * @response status=400 scenario="手機號碼已經被註冊" {
     *   "errors": "手機號碼已經被註冊.",
     *   "code": 100202
     * }
     *
     * @param StoreCustomerRequest $request
     * @return CustomerResource|Response
     */
    public function store(StoreCustomerRequest $request): CustomerResource|Response
    {
        $validator = $request->getValidatorInstance();

        try {
            if ($validator->fails()) {
                throw new InvalidArgumentException(
                    $validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value
                );
            }

            $data = $request->validated();

            $authUser = $this->authService->user();

            // 根據使用者的國籍，選擇保留或移除電子郵件或手機號碼
            switch ($authUser->citizenship) {
                case CustomerCitizenship::FOREIGN->value:
                    Arr::forget($data, 'email');
                    break;

                case CustomerCitizenship::NATIVE->value:
                    Arr::forget($data, 'phone');
                    break;
            }

            // 如果有上傳新的頭像，儲存圖片路徑
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars');
                $data['avatar'] = $avatarPath;
            }

            // 如果電子郵件已經被註冊，則拋出異常.
            $email = Arr::get($data, 'email');
            if ($email && $email != $authUser->email && Customer::whereEmail($email)->exists()) {
                throw new Exception(
                    "電子信箱已經被註冊.", StatusCode::CUSTOMER_EMAIL_EXISTS->value
                );
            }

            // 如果手機號碼已經被註冊，則拋出異常.
            $phone = Arr::get($data, 'phone');
            if ($phone && $phone != $authUser->phone && Customer::wherePhone($phone)->exists()) {
                throw new Exception(
                    "手機號碼已經被註冊.", StatusCode::CUSTOMER_PHONE_EXISTS->value
                );
            }

            $authUser->fill($data);
            if ($authUser->isDirty()) {
                $authUser->update();
            }

            $issueCouponType = [CouponEnums::TYPE_INFORMATION_COMPLETE, CouponEnums::TYPE_BIRTHDAY];

            foreach ($issueCouponType as $couponType) {
                $this->couponService->generateCouponsForCustomer($couponType, $authUser);
            }

            // 返回成功響應
            return new CustomerResource($authUser);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
