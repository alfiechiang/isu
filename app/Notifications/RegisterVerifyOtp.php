<?php

namespace App\Notifications;

use App\Mail\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Sms\SmsMessage;

class RegisterVerifyOtp extends Notification
{
    use Queueable;

    public ?string $driver;

    public ?string $appName;

    public int $validity;

    public string $lang;

    public function __construct($validity,$lang, $driver = null)
    {
        $this->driver = $driver;
        $this->validity = $validity / 60;
        $this->lang=$lang;
        $this->appName = config('app.name');
    }

    
    public function via(object $notifiable)
    {
        if (filter_var($notifiable->identifier, FILTER_VALIDATE_EMAIL)) {
            return ['mail'];
        }

        if (preg_match('/^9\d{8}$/', $notifiable->identifier) === 1) {

            return ['sms'];
        }

        return [];
    }

    public function toMail(object $notifiable): VerifyEmail
    {
        return (new VerifyEmail(validity: $this->validity, code: $notifiable->token))
            ->to($notifiable->identifier);
    }

    public function toSms(object $notifiable): SmsMessage
    {

        switch ($this->lang) {
            case 'cn':
                $content = "【愛嬉遊聯盟】，您的驗證碼為 {$notifiable->token}，{$this->validity}分鐘內有效。請勿將此驗證碼提供給其他人或愛嬉遊聯盟員工。";
                break;
            case 'en':
                $content="[I See You Hotel Alliance] Your verification code is:{$notifiable->token},
                valid for {$this->validity} minutes. Please do not share this verification 
                code with anyone or Love Fun League staff.";
                break;
            default:
                $content = "【愛嬉遊聯盟】，您的驗證碼為 {$notifiable->token}，{$this->validity}分鐘內有效。請勿將此驗證碼提供給其他人或愛嬉遊聯盟員工。";

        }

        if(!empty($notifiable->coupon_code)){
            switch ($this->lang) {
                case 'cn':
                    $content = "【愛嬉遊聯盟】，感謝您獲得此優惠卷 {$notifiable->coupon_code}";
                    break;
                case 'en':
                    $content="[I See You Hotel Alliance] thank you for getting this coupon {$notifiable->coupon_code}";
                    break;
                default:
                    $content = "【愛嬉遊聯盟】，感謝您獲得此優惠卷 {$notifiable->coupon_code}";
            }
        }

        return (new SmsMessage)
            ->content($content)
            ->driver($this->driver);
    }
}
