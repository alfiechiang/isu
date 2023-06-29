<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Factory;

class Phone implements Rule
{

    public function passes($attribute, $value): bool
    {
        // 使用 laravel 的 email:rfc,dns 驗證規則來驗證電子信箱
        $validator = app(Factory::class)->make(
            [$attribute => $value],
            [$attribute => 'phone']
        );

        if ($validator->fails()) {
            return false;
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
