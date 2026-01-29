<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;

trait SendsWhatsApp
{
    protected function sendWhatsApp($phone,$message,$file = null){
        $user = auth()->user();
        // $api_url = $user->api->url;
        $api_url = "http://wabot.adxventure.com/api/user/send-media-message";
     
        $messageData = [
            'recipient' => '919997294527',
            'apikey' => 'wb_4mBjE3IfwFs_bot',
            'text' => $message,
        ];
        if ($file) {
            $messageData['file'] = 'https://newcrm.dsom.in/' . $file;
        }
        
        $responseText = Http::get($api_url, $messageData);
        if ($responseText->successful()) {
            return true;
        } else {
            return false;
        }
    }
}
