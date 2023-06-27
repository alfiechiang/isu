<?php

namespace App\Services\StoreRole;

use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Http\Requests\Customers\RegisterRequest;
use App\Models\Customer;
use Exception;
use App\Exceptions\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    /**
     * 用戶認證 Guard 名稱.
     *
     * @var string
     */
    protected string $guard;

    /**
     * 建立一個新的 AuthService 實例.
     *
     * @param string $guard
     * @return void
     */
    public function __construct(string $guard = 'employee')
    {
        $this->guard = $guard;
    }

    /**
     * 刷新 access_token.
     *
     * @return array
     *
     */
    public function refresh(): array
    {
        try {
            $token = Auth::guard($this->guard)->refresh();
        } catch (JWTException $exception) {
            throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
        }

        return $this->respondWithToken($token);
    }

    /**
     * 登出當前用戶.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard($this->guard)->logout();
    }

    /**
     * 獲取當前認證的用戶實例.
     *
     * @return Customer|null
     */
    public function user(): ?Authenticatable
    {
        return Auth::guard($this->guard)->user();
    }

    /**
     * 登入使用者並傳回 access_token.
     *
     * @param array $credentials 使用者的登入憑證.
     *  - string 'identifier': 必填
     *  - string 'password': 密碼，必填
     *
     * @return array|null access_token，如果登入失敗，則為 null.
     * @throws AuthenticationException
     * @throws Exception
     */
    public function login(array $credentials): ?array
    {
        // 以電子郵件嘗試登入使用者.
        if (!$token = Auth::guard($this->guard)->attempt(['username' => $credentials['identifier'], 'password' => $credentials['password']])) {
            // 如果登入失敗，返回 throw AuthenticationException.
            throw new AuthenticationException(
                '輸入的帳號或密碼錯誤.',
                StatusCode::STORE_ACCOUNT_INVALID_CREDENTIALS->value
            );
        }

        $user = Auth::guard($this->guard)->user();

//        switch ($user->status) {
//            case CustomerStatus::DISABLED->value:
//                throw new Exception(
//                    '顧客帳戶已經停用.',
//                    StatusCode::STORE_ACCOUNT_STATUS_DISABLED->value
//                );
//        }

        // 返回 access_token、token 類型和到期時間.
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array 包含以下三個屬性的物件：
     *  - access_token (string)
     *  - token_type (string)
     *  - expires_in (int)：有效時間，單位為分鐘
     */
    public function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard($this->guard)->factory()->getTTL() * 60
        ];
    }
}
