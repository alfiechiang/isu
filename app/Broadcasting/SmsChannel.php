<?php

namespace App\Broadcasting;

use App\Sms\SmsManager;
use App\Sms\SmsMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * @var SmsManager
     */
    protected SmsManager $manager;

    /**
     * SmsChannel constructor.
     *
     * @param SmsManager $manager
     */
    public function __construct(SmsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $notifiable
     * @param Notification $notification
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $numbers = (array) $notifiable->routeNotificationFor('sms');
        if (empty($numbers)) {
            return;
        }
        /** @var SmsMessage $message */
        $message = $notification->toSms($notifiable);
        foreach ($numbers as $number) {
            $this->manager->driver($message->driver)->send($number, $message);
        }
    }
}
