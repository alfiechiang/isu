<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Services\StoreRole\AuthService;
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
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 處理商店登入.
     *
     * @group Stores
     *
     * @bodyParam identifier string required 帳號.Example: isu.store
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
     *   "errors": "輸入的帳號或密碼錯誤.",
     *   "code": 106001
     * }
     * @response status=400 scenario="顧客帳戶停用" {
     *   "errors": "帳戶已經停用.",
     *   "code": 106002
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
                'identifier' => 'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException(
                    $validator->errors()->first(),
                    StatusCode::INVALID_ARGUMENT->value
                );
            }

            // 呼叫 AuthService 的 login 方法進行身份驗證
            $result = $this->authService->login($credentials);
            $result['user'] = $this->authService->user();

            // 返回成功響應
            return response($result);

        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 登出當前已認證的商店.
     *
     * @group Stores
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
     * 刷新商店的訪問令牌.
     *
     * @group Stores
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
}
