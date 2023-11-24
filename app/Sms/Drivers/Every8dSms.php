<?php

namespace App\Sms\Drivers;

use App\Sms\SmsMessage;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class Every8dSms
{
    const API_URL = 'https://api.e8d.tw/API21/HTTP/sendSMS.ashx';

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var array
     */
    protected array $config;

    /**
     * MitakeSms constructor.
     *
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;

    }

    public function send(string $to, SmsMessage $message,string $country_code): array
    {


        $response= $this->client->get(static::API_URL, [
            'query' => [
                'UID' => $this->config['username'],
                'PWD' => $this->config['password'],
                'SB' => '',
                'DEST'  => $country_code.$to,
                'MSG'   => $message->content,
            ],
        ]);
        $contents = $response->getBody()->getContents();
        $record = str_getcsv($contents, ',');

        if($record[0] <= 0){
            throw new Exception($record[1]);
        }

        return [
            'Credit' => (float)$record[0],
            'Sent' => (int)$record[1],
            'Cost' => (float)$record[2],
            'Unsent' => (int)$record[3],
            'BatchID' => $record[4],
        ];
    }
}
