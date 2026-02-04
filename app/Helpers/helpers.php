<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

if (!function_exists('sendMail')) {
    /**
     * Send an email using Laravel's built-in Mail Facade.
     * * @param string $to Recipient email
     * @param string $subject Email Subject
     * @param string $header Header title
     * @param string|null $footer Footer text
     * @param string $message Main body content (HTML allowed)
     * @return bool
     */
    function sendMail($to, $subject, $header, $footer, $message)
    {
        try {
            // Use config values or fallback
            $fromName = config('mail.from.name', 'TMS - Adxventure');
            $fromEmail = config('mail.from.address', 'info@adxventure.com');

            // Data to pass to the view
            $data = [
                'header' => $header,
                'message' => $message, // Ensure your view uses {!! $message !!}
                'footer' => $footer,
            ];

            // Send using Laravel Mail Facade
            // Note: Ensure 'admin.email.mail' view exists.
            // If not, you can use 'emails.default' or create the view.
            Mail::send('admin.email.mail', $data, function ($mail) use ($to, $subject, $fromEmail, $fromName) {
                $mail->to($to)->from($fromEmail, $fromName)->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Mail Sending Failed to {$to}: " . $e->getMessage());
            return false;
        }
    }
}
### send mail
if (!function_exists('sendLaravelMail')) {
    function sendLaravelMail($to, $subject, $header, $footer, $message)
    {
        try {
            $fromName = config('mail.from.name');
            $fromEmail = config('mail.from.address');

            $data = [
                'header' => $header,
                'message' => $message,
                'footer' => $footer,
            ];

            Mail::send(null, [], function ($mail) use ($to, $subject, $data, $fromEmail, $fromName) {
                $htmlContent = "
                    <h3>{$data['header']}</h3>
                    <p>{$data['message']}</p>
                    <br>
                    <p>{$data['footer']}</p>
                ";

                $mail->to($to)->from($fromEmail, $fromName)->subject($subject)->html($htmlContent);
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

if (!function_exists('addOrdinalSuffix')) {
    /**
     * Add 'st', 'nd', 'rd', 'th' suffix to a number.
     * * @param int $number
     * @return string
     */
    function addOrdinalSuffix($number)
    {
        if (!in_array($number % 100, [11, 12, 13])) {
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
