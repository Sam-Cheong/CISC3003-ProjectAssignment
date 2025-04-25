<?php
// helpers/Mailer.php

declare(strict_types=1);

// 1. Use Composer’s autoloader rather than manual require of src files
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /**
     * Send a 6‑digit verification code via SMTP.
     */
    public static function sendVerification(string $to, int $code): bool
    {
        $mail = new PHPMailer(true);

        try {
            // 2. SMTP settings from .env
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];

            // 3. For Gmail on port 465 use implicit TLS (SMTPS)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = (int) $_ENV['MAIL_PORT'];

            $mail->setFrom('opchitc@gmail.com', 'Course Enrollment System');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Your 6‑Digit Verification Code';
            $mail->Body    = "<p>Your code is: <strong>{$code}</strong></p>
                              <p>It expires in 10 minutes.</p>";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mailer error (' . $e->getMessage() . ')');
            return false;
        }
    }

    /**
     * Send a password‑reset link.
     *
     * @param string $to     Recipient email
     * @param string $link   URL with reset token
     */
    public static function sendPasswordReset(string $to, string $link): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = (int) $_ENV['MAIL_PORT'];

            $mail->setFrom('opchitc@gmail.com', 'Course Enrollment System');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <p>You requested a password reset. Click the link below to choose a new password:</p>
                <p><a href=\"{$link}\">Reset my password</a></p>
                <p>This link will expire in 1 hour.</p>
            ";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
