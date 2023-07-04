<?php

namespace App\Http\Controllers\Customers;

use App\Coupon\CouponEnums;
use App\Coupon\CouponService;
use App\Enums\CustomerCitizenship;
use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Exceptions\ErrException;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Resources\Customers\CustomerResource;
use App\Http\Response;
use App\Models\Customer;
use App\Services\CustomerRole\AuthService;
use App\Services\CustomerRole\CustomerService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
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

    public function index()
    {
        $authUser = $this->authService->user();
        return Response::format(200,$authUser,'請求成功');
    }

    public function store(StoreCustomerRequest $request)
    {
        $validator = $request->getValidatorInstance();

        try {
            if ($validator->fails()) {
                throw new ErrException($validator->errors()->first());
            }

            $data = $request->all();
            $authUser = $this->authService->user();
            // 如果電子郵件已經被註冊，則拋出異常.
            $email = Arr::get($data, 'email');
            if ($email && $email != $authUser->email && Customer::whereEmail($email)->exists()) {
                throw new ErrException('電子信箱已經被註冊');
            }

            // 如果手機號碼已經被註冊，則拋出異常.
            $phone = Arr::get($data, 'phone');
            if ($phone && $phone != $authUser->phone && Customer::wherePhone($phone)->exists()) {
                throw new ErrException('手機號碼已經被註冊');
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
            return Response::success();
        } catch (\Exception $e) {
            // 返回失敗響應
            return Response::errorFormat($e);
        }
    }
}
