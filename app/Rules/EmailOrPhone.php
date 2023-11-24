<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Factory;

class EmailOrPhone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // 使用 laravel 的 email:rfc,dns 驗證規則來驗證電子信箱
        $validator = app(Factory::class)->make(
            [$attribute => $value],
            [$attribute => 'email']
        );

        // 如果電子信箱地址驗證失敗，再驗證輸入值是否為台灣手機號碼
        if (!$validator->passes()) {
           // return preg_match('/^09\d{8}$/', $value) === 1;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a valid email address or phone number.';
    }
}
