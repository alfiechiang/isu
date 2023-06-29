<?php

namespace App\Http\Controllers\Customers;

use App\Enums\CustomerStatus;
use App\Enums\StatusCode;
use App\Models\Customer;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function __construct( SocialLoginService $socialLoginService,AuthService $authService)
    {
        $this->socialLoginService = $socialLoginService;
        $this->authService = $authService;

    }

    /**
     * @param Request $request
     * @param $provider_name
     * @return Response
     */
    public function auth(Request $request, $provider_name): Response
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
                
                    $user->update(['status' => CustomerStatus::ENABLED]);
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


    public function register(Request $request, $provider_name): Response
    {


        $accessToken = $request->get('access_token');
        $email = $request->get('email');
        try {
            $validator = Validator::make(['access_token' => $accessToken, 'email' => $email], [
                'access_token' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            $providerUser = $this->socialLoginService->auth($provider_name, $accessToken);

            if (!$providerUser['id']) {
                throw new Exception('找不到社群帳號 ID', StatusCode::SOCIAL_LOGIN_ERROR->value);
            }

            
            $socialAccount = SocialAccount::where('provider_name', $provider_name)->where('provider_id', $providerUser['id'])->first();

            if ($socialAccount) {
                throw new Exception('社群帳號已經完成註冊', StatusCode::SOCIAL_LOGIN_EXIST->value);
            }

            
            if (Customer::whereEmail($email)->exists()) {
                throw new Exception("電子信箱已經被註冊.", StatusCode::CUSTOMER_EMAIL_EXISTS->value);
            }

            // $generate = $this->otpService->generate($email);
            // $generate->otp->notify(new RegisterVerifyOtp(validity: $generate->validity, verifyUrl: $verifyUrl));

            $user = $this->authService->createCustomer([
                'email' => $email,
                'name' => $providerUser['name'],
            ]);

            $user->social_accounts()->create([
                'provider_name' => $provider_name,
                'provider_id' => $providerUser['id'],
                'access_token' => $accessToken,
            ]);

            return response(['message' => '註冊成功.']);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }

    }

}