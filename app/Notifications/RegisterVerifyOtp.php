<?php

namespace App\Notifications;

use App\Mail\VerifyEmail;
use App\Models\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Sms\SmsMessage;

class RegisterVerifyOtp extends Notification
{
    use Queueable;

    /** @var string|null */
    public ?string $driver;

    /** @var string|null */
    public ?string $appName;

    /** @var int */
    public int $validity;

    /**
     * Create a new notification instance.
     *
     * @param null $driver
     */
    public function __construct($validity, $driver = null)
    {
        $this->driver = $driver;
        $this->validity = $validity / 60;
        $this->appName = config('app.name');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (filter_var($notifiable->identifier, FILTER_VALIDATE_EMAIL)) {
            return ['mail'];
        }

        if (preg_match('/^9\d{8}$/', $notifiable->identifier) === 1) {
            return ['sms'];
        }

        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return VerifyEmail
     */
    public function toMail(object $notifiable): VerifyEmail
    {
        return (new VerifyEmail(validity: $this->validity, code: $notifiable->token))
            ->to($notifiable->identifier);
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param object $notifiable
     * @return SmsMessage
     */
    public function toSms(object $notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->content("歡迎使用「{$this->appName}」，您的驗證碼為 {$notifiable->token}，{$this->validity}分鐘內有效。")
            ->driver($this->driver);
    }
}
