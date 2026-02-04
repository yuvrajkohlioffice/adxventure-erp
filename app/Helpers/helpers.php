<?php

use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;
use App\Models\{RideCharge,EarlyLateFee,Payment};
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
### send mail 
if (!function_exists('sendMail')) {
    function sendMail($to, $subject, $header, $footer, $message) {
        try {
            $fromName  = 'TMS - Adxventure';
            $fromEmail = 'info@adxventure.com';

            // 1. Render the HTML
            $html = view('admin.email.mail', [
                'header'  => $header,
                'message' => $message,
                'footer'  => $footer,
            ])->render();

            // 2. Prepare Headers
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "Reply-To: $fromEmail\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            // 3. Convert Array to String for native mail() if needed
            $recipientString = is_array($to) ? implode(', ', $to) : $to;

            // 4. Send and Log
            if (mail($recipientString, $subject, $html, $headers)) {
                \Log::info("Mail Sent Successfully to: " . $recipientString);
                return true;
            } else {
                \Log::warning("Mail() returned false for: " . $recipientString);
                return false;
            }

        } catch (\Exception $e) {
            \Log::error("Mail Exception for " . (is_array($to) ? implode(', ', $to) : $to) . ": " . $e->getMessage());
            return false;
        }
    }
}

### send mail 
if (!function_exists('sendLaravelMail')) {
    function sendLaravelMail($to, $subject, $header, $footer, $message)
    {
        try {
            $fromName  = config('mail.from.name', 'TMS Adxventure');
            $fromEmail = config('mail.from.address', 'info@adxventure.com');

            $data = [
                'header'  => $header,
                'body'    => $message,
                'footer'  => $footer,
            ];

            Mail::send([], [], function ($mail) use ($to, $subject, $data, $fromEmail, $fromName) {
                $htmlContent = "
                    <div style='font-family: sans-serif;'>
                        <h2>{$data['header']}</h2>
                        <div>{$data['body']}</div>
                        <hr>
                        <div>{$data['footer']}</div>
                    </div>
                ";

                $mail->to($to)
                    ->from($fromEmail, $fromName)
                    ->subject($subject)
                    ->html($htmlContent);
            });

            // ✅ LOG SUCCESS
            \Log::info("Mail Sent Successfully to: " . (is_array($to) ? implode(', ', $to) : $to));
            
            return true;

        } catch (\Exception $e) {
            // ❌ LOG FAILURE
            \Log::error("Mail Failure to " . (is_array($to) ? implode(', ', $to) : $to) . ": " . $e->getMessage());
            return false;
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
