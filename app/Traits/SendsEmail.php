<?php

namespace App\Traits;
use Illuminate\Support\Facades\Mail;

trait SendsEmail
{
    public function sendEmail($to,$subject,$message)
    {
        if (empty($to)) {
            return false;
        }
        try{
            // Format sender
            $fromName = 'DSOM';
            $fromEmail = 'contact@dsom.in';

            // Prepare headers
            $boundary = md5(uniqid(time()));
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";

            // Prepare body
            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $message . "\r\n";
            $body .= "--{$boundary}--";

            // Send mail to each email
            if(mail($to, $subject, $body, $headers)){
                return true;
            }else{
                return false;
            }     
        }catch (\Exception $e) {
            logger()->error('Email send failed', [
                'email' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
