<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Mail\VerifyEmail;
use App\Models\Otp;
use App\Services\CustomerRole\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    protected $otpService;

    public function __construct(OtpService $otpService)
    {

        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request){
        $identifier= $request->get('identifier');
        $generate = $this->otpService->generate($identifier, null);
        $token=$generate->otp->token;
        Mail::to($identifier)->send(new VerifyEmail(900,$token));
    }

    public function checkOtp(Request $request)
    {
        $verify_code = $request->get('verify_code');
        $otp = Otp::where('identifier', $request->email)->first();
        $s1 = strtotime(date('Y-m-d H:i:s'));
        $s2 = strtotime($otp->updated_at);
        if ($s1 - $s2 > 900) { // 900秒 
            return Response::format(40001, [], "驗證碼已失效");
        }
        if (is_null($otp)) {
            return Response::format(40001, [], "驗證錯誤");
        }
        if ($verify_code !== $otp->token) {
            return Response::format(40001, [], "驗證錯誤");
        }
        return Response::success();
    }

}
