<?php

use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;
use App\Models\{RideCharge, EarlyLateFee, Payment};
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
### send mail 
if (!function_exists('sendMail')) {
    function sendMail($to, $subject, $header, $message, $footer = null)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings from .env
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'bh-in-28.webhostbox.net');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'info@adxventure.com');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls') === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = env('MAIL_PORT', 587);

            // Sender & Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'info@adxventure.com'), env('MAIL_FROM_NAME', 'TMS - Adxventure'));

            if (is_string($to) && str_contains($to, ',')) {
                $to = explode(',', $to);
            }

            if (is_array($to)) {
                foreach ($to as $address) {
                    $mail->addAddress(trim($address));
                }
            } else {
                $mail->addAddress(trim($to));
            }

            // Render Blade Email Template
            // Note: We pass 'body' instead of 'message' to avoid conflicts
            $html = view('admin.email.mail', [
                'header'  => $header,
                'body'    => $message,
                'footer'  => $footer,
            ])->render();

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;

            $mail->send();
            Log::info("Mail Sent successfully to: " . (is_array($to) ? implode(',', $to) : $to));
            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}

### send mail 


if (!function_exists('sendLaravelMail')) {
    function sendLaravelMail($to, $subject, $header, $footer, $message)
    {
        $mail = new PHPMailer(true);

        try {
            // --- Server Settings ---
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'bh-in-28.webhostbox.net');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'info@adxventure.com');
            $mail->Password   = env('MAIL_PASSWORD'); // Ensure this is set in .env
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls') === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = env('MAIL_PORT', 587);

            // --- Sender & Recipient ---
            $fromName  = config('mail.from.name', 'TMS Adxventure');
            $fromEmail = config('mail.from.address', 'info@adxventure.com');

            $mail->setFrom($fromEmail, $fromName);

            // Handle multiple recipients if $to is an array
            if (is_array($to)) {
                foreach ($to as $address) {
                    $mail->addAddress($address);
                }
            } else {
                $mail->addAddress($to);
            }

            // --- Content ---
            $mail->isHTML(true);
            $mail->Subject = $subject;

            // Use your custom HTML layout
            $mail->Body = "
                <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px;'>
                    <h2 style='color: #333;'>{$header}</h2>
                    <div style='line-height: 1.6; color: #555;'>{$message}</div>
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <div style='font-size: 12px; color: #888;'>{$footer}</div>
                </div>
            ";

            $mail->send();

            // ✅ LOG SUCCESS
            \Log::info("Mail Sent via PHPMailer successfully to: " . (is_array($to) ? implode(', ', $to) : $to));
            return true;
        } catch (Exception $e) {
            // ❌ LOG FAILURE
            \Log::error("PHPMailer Failure to " . (is_array($to) ? implode(', ', $to) : $to) . ": " . $mail->ErrorInfo);
            return false;
        }
    }
}


if (!function_exists('addOrdinalSuffix')) {
    function addOrdinalSuffix($number)
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:
                    return $number . '<sup>st</sup>';
                case 2:
                    return $number . '<sup>nd</sup>';
                case 3:
                    return $number . '<sup>rd</sup>';
            }
        }
        return $number . '<sup>th</sup>';
    }
}
