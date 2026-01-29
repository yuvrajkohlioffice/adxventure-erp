<?php

use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;
use App\Models\{RideCharge,EarlyLateFee,Payment};
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
### send mail 
if(!function_exists('sendMail')){
    function sendMail($to,$subject,$header,$footer,$message){
        try {
            // Sender info
            $fromName  = 'TMS - Adxventure';
            $fromEmail = 'info@adxventure.com';

            // Render Blade email template
            // In your mail.blade.php use {!! $message !!} instead of {{ $message }}
            $html = view('admin.email.mail', [
                    'header'  => $header,
                    'message' => $message,
                    'footer' =>$footer,
            ])->render();

            // Headers
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "Reply-To: $fromEmail\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            // Send
            if (mail($to,$subject, $html, $headers)) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}



### send mail 
if (!function_exists('sendLaravelMail')) {
    function sendLaravelMail($to, $subject, $header, $footer, $message)
    {
        try {
            $fromName  = config('mail.from.name');
            $fromEmail = config('mail.from.address');

            $data = [
                'header'  => $header,
                'message' => $message,
                'footer'  => $footer,
            ];

            Mail::send(NULL, [], function ($mail) use ($to, $subject, $data, $fromEmail, $fromName) {
                $htmlContent = "
                    <h3>{$data['header']}</h3>
                    <p>{$data['message']}</p>
                    <br>
                    <p>{$data['footer']}</p>
                ";

                $mail->to($to)
                    ->from($fromEmail, $fromName)
                    ->subject($subject)
                    ->html($htmlContent);
            });

            if (count(Mail::failures()) > 0) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}


if(!function_exists('addOrdinalSuffix')){
    function addOrdinalSuffix($number) {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:  return $number . '<sup>st</sup>';
                case 2:  return $number . '<sup>nd</sup>';
                case 3:  return $number . '<sup>rd</sup>';
            }
        }
        return $number . '<sup>th</sup>';
    }
}