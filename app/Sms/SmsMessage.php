<?php

namespace App\Sms;

class SmsMessage
{
    /** @var string */
    public string $content;

    /** @var string|null */
    public ?string $driver = null;

    /**
     * @param string $content
     *
     * @return SmsMessage
     */
    public function content(string $content): SmsMessage
    {
        $this->content = $content;

        return $this;
    }

    public function driver($driver): SmsMessage
    {
        $this->driver = $driver;

        return $this;
    }
}
