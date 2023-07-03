<?php

namespace App\Services\CustomerRole;

use App\Enums\StatusCode;
use App\Models\Otp;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class OtpService
{
    /**
     * 產生 OTP 碼
     *
     * @param string $identifier 辨識用戶的字串
     * @return object 回傳包含以下屬性的物件
     *  - string 'message'
     *
     * @throws InvalidArgumentException|Exception
     */
    public function generate(string $identifier,$country_code): object
    {
        $validity = Otp::AUTH_CODE_TTL;
        $digits = 6;

        $limit = Otp::where('identifier', $identifier)
            ->where('created_at', '>=', Carbon::now()->subSeconds(Otp::SEND_INTERVAL))
            ->count();

        if ($limit >= Otp::SEND_LIMIT) {
           // throw new Exception('操作過於頻繁，請稍後再試.', StatusCode::OTP_SEND_LIMIT->value);
        }

        // 驗證參數格式
        $validator = Validator::make(
            compact('identifier', 'digits', 'validity'),
            [
                'identifier' => 'required|string|max:255',
                'digits' => 'required|integer|between:4,6',
                'validity' => 'required|integer|min:60',
            ]
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first(), StatusCode::INVALID_ARGUMENT->value);
        }

        // 產生 OTP 碼
        $token = $this->generatePin($digits);
        // 儲存至資料庫
        $otp=Otp::where('identifier',$identifier)->first();
        if (is_null($otp)){
            $otp = Otp::create([
                'identifier' => $identifier,
                'token' => $token,
                'country_code'=>$country_code,
                'expired_at' => Carbon::now()->addSeconds($validity),
            ]);
        }else{
            $otp->token=$token;
            $otp->expired_at=Carbon::now()->addSeconds($validity);
            $otp->save();
        }

        return (object)[
            'otp' => $otp,
            'validity' => $validity,
            'message' => 'OTP generated',
        ];
    }

    /**
     * 驗證 OTP 碼
     *
     * @param string $identifier 辨識用戶的字串
     * @param string $token OTP 碼
     * @return boolean
     *
     * @throws Exception
     */
    public function validate(string $identifier, string $token): bool
    {
        // 從資料庫搜尋 OTP 碼
        $otp = Otp::where('identifier', $identifier)
            ->where('token', $token)
            ->first();

        if ($otp == null) {
            throw new Exception('OTP does not exist', StatusCode::OTP_NOT_EXIST->value);
        }

        if (Carbon::createFromFormat('Y-m-d H:i:s', $otp->expired_at)->timestamp < Carbon::now()->timestamp) {
            $otp->valid = false;
            $otp->save();

            throw new Exception('OTP Expired', StatusCode::OTP_EXPIRED->value);
        } elseif ($otp->valid == false) {
            throw new Exception('OTP is not valid', StatusCode::OTP_NOT_EXIST->value);
        } else {
            $otp->valid = false;
            $otp->save();

            return true;
        }
    }

    /**
     * 產生指定位數的隨機 PIN 碼
     *
     * @param int $digits PIN 碼的位數
     * @return string 回傳產生的 PIN 碼
     */
    private function generatePin(int $digits): string
    {
        $pin = '';

        for ($i = 0; $i < $digits; $i++) {
            $pin .= mt_rand(0, 9);
        }

        return $pin;
    }
}

