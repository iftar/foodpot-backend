<?php

namespace App\Services\All;

use App\Notifications\SmsMessage;
use MessageBird\Client as Client;
use MessageBird\Objects\Message as Message;

class SmsService
{
    public function __construct()
    {
        $this->enabled           = config('sms.enabled');
        $this->messagebird       = new Client(config('sms.api_key'));
        $this->originator_number = config('sms.originator_number');
    }

    public function sendMessage(SmsMessage $smsMessage)
    {
        if ( ! $this->enabled) {
            return false;
        }

        if ( ! $smsMessage->hasPhoneNumbers() || ! $smsMessage->hasMessage()) {
            return false;
        }

        $message             = new Message();
        $message->originator = $this->originator_number;
        $message->recipients = $smsMessage->getPhoneNumbers();
        $message->body       = $smsMessage->getMessage();

        return $this->messagebird->messages->create($message);
    }
}
