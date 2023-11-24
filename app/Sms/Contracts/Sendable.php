<?php

namespace App\Sms\Contracts;

use App\Sms\SmsMessage;
use GuzzleHttp\Psr7\Response;

interface Sendable
{
    public function send(string $to, SmsMessage $message): ?Response;
}
