<?php

namespace App\Http\Controllers\Customers;

use App\Enums\StatusCode;
use App\Notifications\RegisterVerifyOtp;
use App\Rules\EmailOrPhone;
use App\Services\CustomerRole\OtpService;
use Illuminate\Http\Request;
use App\Http\Response;
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

    public function sendOtp(Request $request)
    {
        // 從請求中取得識別符、驗證碼有效時間以及驗證碼位數.
        $identifier = $request->get('identifier');
        $country_code=$request->get('country_code');

        try {
            // 驗證輸入數據.
            $validator = Validator::make(['identifier' => $identifier], [
                // 檢查輸入是否為電子郵件或手機號碼.
                'identifier' => ['required', new EmailOrPhone],
            ]);

            if ($validator->fails()) {
                return Response::error();
            }

            // 生成驗證碼.
            $generate = $this->otpService->generate($identifier,$country_code);
            $generate->otp->notify(new RegisterVerifyOtp(validity: $generate->validity));
            // 返回成功響應.
            return Response::success();
        } catch (\Exception $e) {
            // 返回失敗響應.
            return Response::error();
        }
    }
    
    public function checkOtp(Request $request)
    {
        $verify_code = $request->get('verify_code');
        if ($verify_code !== session('verify_code')) {
            return Response::format(40001, [], "手機驗證錯誤");
        }

        session()->forget('verify_code');
        
        return Response::success();

    }
}
