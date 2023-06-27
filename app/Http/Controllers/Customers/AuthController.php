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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AuthController extends Controller
{
    /**
     * The AuthService instance.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * The OtpService instance.
     *
     * @var otpService
     */
    protected OtpService $otpService;

    /**
     * Create a new controller instance.
     *
     * @param OtpService $otpService
     * @param AuthService $authService
     */
    public function __construct(OtpService $otpService, AuthService $authService)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
    }

    /**
     * 處理用戶登入.
     *
     * @group Customers
     *
     * @bodyParam identifier string required 用戶電子郵件地址或手機號碼.Example: user@example.com
     * @bodyParam password string required 用戶密碼.Example: password123
     *
     * @response scenario=success {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImF...",
     *   "token_type": "Bearer",
     *   "expires_in": 3600
     * }
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The password field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="錯誤的登入資料" {
     *   "errors": "輸入的帳戶信箱或密碼錯誤.",
     *   "code": 100001
     * }
     * @response status=400 scenario="顧客帳戶停用" {
     *   "errors": "顧客帳戶已經停用.",
     *   "code": 100002
     * }
     * @response status=400 scenario="顧客帳戶未認證" {
     *   "errors": "顧客帳戶尚未完成驗證.",
     *   "code": 100003
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        // 取得輸入數據
        $credentials = $request->only('identifier', 'password');

        try {
            // 驗證輸入數據
            $validator = Validator::make($credentials, [
                // 檢查輸入是否為電子郵件或手機號碼
                'identifier' => ['required', new EmailOrPhone],

                // 檢查密碼是否至少為8個字符
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                // 如果驗證失敗，拋出一個異常
                throw new InvalidArgumentException(
                    $validator->errors()->first(),
                    StatusCode::INVALID_ARGUMENT->value
                );
            }

            // 呼叫 AuthService 的 login 方法進行身份驗證
            $result = $this->authService->login($credentials);

            // 返回成功響應
            return response($result);

        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 登出當前已認證的用戶.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @response scenario=success {
     *     "message": "成功登出"
     * }
     *
     * @return Response
     */
    public function logout(): Response
    {
        $this->authService->logout();

        return response(['message' => 'Successfully logged out']);
    }

    /**
     * 刷新用戶的訪問令牌.
     *
     * @group Customers
     *
     * @authenticated
     *
     * @response scenario=success {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImF...",
     *   "token_type": "Bearer",
     *   "expires_in": 3600
     * }
     *
     * @return Response
     */
    public function refresh(): Response
    {
        try {
            // 呼叫 AuthService 的 refresh 方法進行令牌刷新
            $result = $this->authService->refresh();

            // 返回成功響應
            return response($result);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 處理用戶註冊.
     *
     * @group Customers
     *
     * @bodyParam email string 用戶電子郵件.Example: user@example.com
     * @bodyParam phone string 用戶手機號碼.Example: 0912345678
     * @bodyParam password string required 用戶密碼.Example: password123
     * @bodyParam citizenship string required 國籍必須是 foreign, native.Example: foreign
     * @bodyParam token int required OTP 代碼.Example: 839489
     *
     * @apiResource App\Http\Resources\Customers\CustomerResource
     * @apiResourceModel App\Models\Customer
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The password field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="OTP 資料不存在" {
     *   "errors": "OTP does not exist.",
     *   "code": 100102
     * }
     * @response status=400 scenario="OTP 資料過期" {
     *   "errors": "OTP Expired.",
     *   "code": 100103
     * }
     * @response status=400 scenario="OTP 資料已經失效" {
     *   "errors": "OTP is not valid.",
     *   "code": 100104
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
     * @param RegisterRequest $request
     * @return CustomerResource|Response
     */
    public function register(RegisterRequest $request): CustomerResource|Response
    {
        $validator = $request->getValidatorInstance();

        try {
            if ($validator->fails()) {
                throw new InvalidArgumentException(
                    $validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value
                );
            }

            $data = $request->validated();

            $identifier = '';

            switch ($data['citizenship']) {
                case CustomerCitizenship::FOREIGN->value:
                    $identifier = $data['email'];
                    break;
                case CustomerCitizenship::NATIVE->value:
                    $identifier = $data['phone'];
                    break;
            }

            $this->otpService->validate($identifier, $data['token']);

            // 創建用戶
            $customer = $this->authService->createCustomer($data);

            // 返回成功響應
            return new CustomerResource($customer);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
