<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    // 用戶email
    protected string $email;

    // 有效時間，單位為秒
    protected int $validity;

    // 驗證碼
    protected int $code;

    /**
     * 建立新的郵件實例
     *
     * @param int $validity 有效時間，單位為秒
     * @param int $code 驗證碼
     * @throws InvalidArgumentException
     */
    public function __construct(int $validity, int $code)
    {
        $this->code = $code;
        $this->validity = $validity;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' 驗證碼',
        );
    }

    /**
     * Get the message content definition.
     */
    #[Pure] public function content(): Content
    {
        return new Content(
            markdown: 'emails.customers.verify-email',
            with: [
                'code' => (string) $this->code,
                'validity' => (string) $this->validity,
            ],
        );
    }
}
