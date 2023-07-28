<?php

namespace App\Services\CustomerRole;

use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Http\Requests\Customers\RegisterRequest;
use App\Models\Customer;
use Exception;
use App\Exceptions\AuthenticationException;
use App\Exceptions\ErrException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\Utils;
use App\Models\SocialAccount;

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
    public function __construct(string $guard = 'customers')
    {
        $this->guard = $guard;
    }

    public function loginUser($user): void
    {
        Auth::guard($this->guard)->login($user);
    }

    public function handleLogin($token): array
    {
        $user = Auth::guard($this->guard)->user();

        if ($user->status == CustomerStatus::DISABLED->value) {
            throw new Exception(
                '顧客帳戶已經停用.',
                StatusCode::ACCOUNT_STATUS_DISABLED->value
            );
        }

        if ($user->status == CustomerStatus::UNVERIFIED->value) {
            throw new AuthenticationException(
                '尚未完成信箱驗證.',
                StatusCode::ACCOUNT_STATUS_UNVERIFIED->value
            );
        }
        
        // 返回 access_token、token 類型和到期時間.
        return $this->respondWithToken($token);
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

    public function login(array $credentials): ?array
    {
      
        if (!$token = Auth::guard($this->guard)->attempt(['phone' => $credentials['identifier'], 'password' => $credentials['password']])) {
            // 如果登入失敗，返回 throw AuthenticationException.
            throw new AuthenticationException(
                '輸入的帳戶手機或密碼錯誤.',
                StatusCode::AUTH_INVALID_CREDENTIALS->value
            );
        }
    
        $user = Auth::guard($this->guard)->user();

        switch ($user->status) {
            case CustomerStatus::DISABLED->value:
                throw new Exception(
                    '顧客帳戶已經停用.',
                    StatusCode::ACCOUNT_STATUS_DISABLED->value
                );
            case CustomerStatus::UNVERIFIED->value:
                throw new Exception(
                    '顧客帳戶尚未完成驗證.',
                    StatusCode::ACCOUNT_STATUS_UNVERIFIED->value
                );
        }

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

    /**
     * 創建新的用戶.
     *
     * @param array $data 傳遞過來的客戶數據.
     *
     * @return Customer 返回創建的客戶對象.
     * @throws Exception
     */
    public function createCustomer(array $data): Customer
    {
        // 如果手機號碼已經被註冊，則拋出異常.
        $phone = Arr::get($data, 'phone');

        if ($phone && Customer::wherePhone($phone)->exists()) {
            throw new ErrException("手機號碼已經被註冊");
        }

        // 創建新的用戶.
        return Customer::create([
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'status' => CustomerStatus::ENABLED->value,
            'guid'=>'ISU'.date('Ymd').rand(1000000,9999999),
            'country_code'=>$data['country_code'],
        ]);
    }

    public function forgetPassword(array $data)
    {
        $customer =Customer::where('phone',$data['phone'])->first();
        $customer->password=$data['password'];
        $customer->save();
    }

    public function resetPassword(array $data)
    {
        $customer =Customer::where('phone',$data['phone'])->first();
        $customer->password=$data['password'];
        $customer->save();
    }

    public function socialAccounts(){
        $authUser = Auth::guard(Utils::getGuardName())->user();
        $coutomer_id=$authUser->id;

        return SocialAccount::where('customer_id',$coutomer_id)->pluck('provider_name');
    }

    public function checkRegister($phone){
        $customer =Customer::where('phone',$phone)->first();
        if(is_null($customer)){
            return false;
        }
        return true;
    }
}
