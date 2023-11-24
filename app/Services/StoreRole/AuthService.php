<?php

namespace App\Services\StoreRole;

use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Exceptions\ErrException;
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

    protected string $guard;

    public function __construct(string $guard = 'employee')
    {
        $this->guard = $guard;
    }

    public function refresh(): array
    {
        try {
            $token = Auth::guard($this->guard)->refresh();
        } catch (JWTException $exception) {
            throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
        }

        return $this->respondWithToken($token);
    }

    public function logout(): void
    {
        Auth::guard($this->guard)->logout();
    }

    public function user(): ?Authenticatable
    {
        return Auth::guard($this->guard)->user();
    }


    public function login(array $credentials)
    {
        // 以電子郵件嘗試登入使用者.
        if (!$token = Auth::guard($this->guard)->attempt(['email' => $credentials['identifier'], 'password' => $credentials['password']])) {         
            throw new ErrException(StatusCode::STORE_ACCOUNT_INVALID_CREDENTIALS->value);
        }

        return $token;
    }

    public function respondWithToken(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard($this->guard)->factory()->getTTL() * 60
        ];
    }
}
