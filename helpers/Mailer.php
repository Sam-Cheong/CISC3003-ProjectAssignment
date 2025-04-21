<?php
// helpers/Mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer
{
    public static function sendVerification(string $to, int $code): bool
    {
        $mail = new PHPMailer(true);
        try {
            // load SMTP creds from .env or config
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int)$_ENV['MAIL_PORT'];

            $mail->setFrom('no-reply@yourdomain.com', 'Your App');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Your 6‑Digit Verification Code';
            $mail->Body    = "<p>Your code is: <strong>{$code}</strong></p>
                              <p>It expires in 10 minutes.</p>";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}