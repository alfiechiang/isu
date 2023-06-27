<?php

namespace App\Http\Controllers\Customers;

use App\Enums\StatusCode;
use App\Notifications\RegisterVerifyOtp;
use App\Rules\EmailOrPhone;
use App\Services\CustomerRole\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class OtpController extends Controller
{
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
     * @return void
     */
    public function __construct(OtpService $otpService)
    {
        // 將注入的 OtpService 實例存儲在該類的私有變量中
        $this->otpService = $otpService;
    }

    /**
     * 發送 OTP 碼.
     *
     * @group Customers
     *
     * @bodyParam identifier string required 用戶電子郵件地址或手機號碼.Example: user@example.com
     *
     * @response scenario=success {
     *   "message": "發送成功."
     * }
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The identifier field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="發送次數限制" {
     *   "errors": "操作過於頻繁，請稍後再試.",
     *   "code": 100101
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function sendOtp(Request $request): Response
    {
        // 從請求中取得識別符、驗證碼有效時間以及驗證碼位數.
        $identifier = $request->get('identifier');

        try {
            // 驗證輸入數據.
            $validator = Validator::make(['identifier' => $identifier], [
                // 檢查輸入是否為電子郵件或手機號碼.
                'identifier' => ['required', new EmailOrPhone],
            ]);

            if ($validator->fails()) {
                // 如果驗證失敗，拋出一個異常.
                throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
            }

            // 生成驗證碼.
            $generate = $this->otpService->generate($identifier);
            $generate->otp->notify(new RegisterVerifyOtp(validity: $generate->validity));

            // 返回成功響應.
            return response(['message' => '發送成功.']);
        } catch (\Exception $e) {
            // 返回失敗響應.
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
