<?php


namespace App\Services;


use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Exception;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $tw_sms_number;

    public function __construct($sid, $token, $tw_sms_number)
    {
        $this->sid = $sid;
        $this->token = $token;
        $this->tw_sms_number = $tw_sms_number;
    }


    /**
     * @param $to
     * @param $message
     * @return array
     * @throws Exception
     */
    public function sendMessage($to, $message)
    {
        try {
            $client = new Client($this->sid, $this->token);
            $client->messages->create($to, [
                'from' => $this->tw_sms_number,
                'body' => $message,
            ]);
            return [];
        } catch (Exception $e) {
            Log::error('Twilio error');
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
