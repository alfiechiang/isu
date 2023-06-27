<x-mail::message>
# 驗證您的電子郵件地址

{{ config('app.name') }} 收到您註冊為 {{ config('app.name') }} 帳戶的要求。

請使用這個驗證碼完成電子郵件地址的驗證程序：

<div style="text-align:center;font-size:36px;margin:30px 0;">{{$code}}</div>

驗證碼將於 10 分鐘後失效。

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
