<?php

namespace App\Http\Controllers\Customers;

use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Http\Response;
use App\Models\Customer;
use App\Models\SocialAccount;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Services\Base\SocialLoginService;
use App\Services\CustomerRole\AuthService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialLoginController extends Controller
{

    protected SocialLoginService $socialLoginService;

    protected AuthService $authService;
    /**
     * Create a new controller instance.
     * @param SocialLoginService $socialLoginService
     * @param AuthService $authService
     */
    public function __construct(SocialLoginService $socialLoginService, AuthService $authService)
    {
        $this->socialLoginService = $socialLoginService;
        $this->authService = $authService;
    }

    public function auth(Request $request, $provider_name)
    {
        $accessToken = $request->get('access_token');

        try {

            $validator = Validator::make(['access_token' => $accessToken], [
                'access_token' => ['required'],
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $providerUser = $this->socialLoginService->auth($provider_name, $accessToken);

            if (!$providerUser['id']) {
                throw new Exception('找不到社群帳號 ID', StatusCode::SOCIAL_LOGIN_ERROR->value);
            }

            $socialAccount = SocialAccount::where('provider_name', $provider_name)->where('provider_id', $providerUser['id'])->first();

            if (!$socialAccount) {
                $registerValidator = Validator::make($providerUser, [
                    'email' => ['required'],
                    'name' => ['required'],
                ]);

                if ($registerValidator->fails()) {
                    throw new Exception($registerValidator->errors()->first(), StatusCode::SOCIAL_LOGIN_MISSING_DATA->value);
                }

                if (!$user = Customer::whereEmail($providerUser['email'])->first()) {
                    $user = $this->authService->createCustomer([
                        'email' => $providerUser['email'],
                        'name' => $providerUser['name'],
                    ]);

                    $user->update(['status' => CustomerStatus::ENABLED->value]);
                }

                $socialAccount = $user->social_accounts()->create([
                    'provider_name' => $provider_name,
                    'provider_id' => $providerUser['id'],
                ]);
            } else {
                $user = $socialAccount->customer;
            }

            $socialAccount->update(['access_token' => $accessToken]);

            $this->authService->loginUser($user);

            $token = JWTAuth::fromUser($user);
            $result = $this->authService->handleLogin($token);

            // 返回成功響應
            return response($result);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }


    public function register(Request $request, $provider_name)
    {
        $accessToken = $request->get('access_token');
        $phone = $request->get('phone');
        $password = $request->get('password');
        $country_code = $request->country_code;
        try {

            $validator = Validator::make(['access_token' => $accessToken, 'phone' => $phone, 'password' => $password, 'country_code' => $country_code], [
                'access_token' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'country_code' => 'required'
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $providerUser = $this->socialLoginService->auth($provider_name, $accessToken);

            if (!$providerUser['id']) {
                throw new ErrorException('找不到社群帳號 ID');
            }

            $socialAccount = SocialAccount::where('provider_name', $provider_name)->where('provider_id', $providerUser['id'])->first();
            if ($socialAccount) {
                throw new ErrorException('社群帳號已經完成註冊');
            }

            $user = $this->authService->createCustomer([
                'phone' => $phone,
                'country_cde' => $country_code,
                'password' => $password
            ]);

            $user->social_accounts()->create([
                'provider_name' => $provider_name,
                'provider_id' => $providerUser['id'],
                'access_token' => $accessToken,
            ]);

            return Response::success();
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function checkoutSocailAccount(Request $request)
    {
        try {
            $provider_name = $request->provider_name;
            $access_token = $request->access_token;
            $exist = $this->socialLoginService->checkoutSocailAccount($provider_name, $access_token);
            return Response::format(200, ['exist' => $exist], '請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function bindSocialAccount(Request $request)
    {
        try {
            $provider_name = $request->provider_name;
            $accessToken = $request->access_token;
            $this->socialLoginService->bindSocialAccount($provider_name, $accessToken);
            return Response::format(200, [], '請求成功');
        } catch (\Exception $e) {
            return Response::errorFormat($e);
        }
    }
}
