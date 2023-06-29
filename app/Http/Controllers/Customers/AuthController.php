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
use App\Http\Response;

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
    public function login(Request $request)
    {
        // 取得輸入數據
        $credentials = $request->only('identifier', 'password');

        try {
            // 驗證輸入數據
            // $validator = Validator::make($credentials, [
            //     // 檢查輸入是否為電子郵件或手機號碼
            //     'identifier' => 'required|phone',

            // ]);

            // if ($validator->fails()) {
            //     // 如果驗證失敗，拋出一個異常
            //     throw new InvalidArgumentException(
            //         $validator->errors()->first(),
            //         StatusCode::INVALID_ARGUMENT->value
            //     );
            // }

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
    public function logout()
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
    public function refresh()
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

    public function register(RegisterRequest $request)
    {

        $validator = $request->getValidatorInstance();

        try {
            if ($validator->fails()) {
                return Response::format(StatusCode::INVALID_ARGUMENT->value,[],"");
            }
            // 創建用戶
            $customer = $this->authService->createCustomer($request->all());

            // 返回成功響應
            return Response::success();
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    public function registerNext(){
        
    }
}
