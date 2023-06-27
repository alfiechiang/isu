<?php

namespace App\Sms\Drivers;

use App\Sms\SmsMessage;
use Psr\Log\LoggerInterface;

class LogSms
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LogSms constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send(string $to, SmsMessage $message): ?Response
    {
        $this->logger->debug('LogSms', [
            'to' => $to,
            'message' => $message->content,
        ]);

        return null;
    }
}
