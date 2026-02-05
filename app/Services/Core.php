<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class Core
{
    /**
     * Send email using PHPMailer
     */
    public static function sendMail($to, $subject, $header, $body, $footer = null, $cc = null)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'bh-in-28.webhostbox.net');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'info@adxventure.com');
            $mail->Password   = env('MAIL_PASSWORD'); 
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls') == 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = env('MAIL_PORT', 587);

            // Sender & recipient
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($to);

            if ($cc) {
                $mail->addCC($cc);
            }

            // Render the Laravel Blade view to a string
            $htmlContent = view('admin.email.mail', [
                'header' => $header,
                'body'   => $body, // avoid conflict with $message
                'footer' => $footer,
            ])->render();

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error: " . $mail->ErrorInfo);
            return $mail->ErrorInfo;
        }
    }
}