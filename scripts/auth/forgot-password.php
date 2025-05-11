<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

try {
    $mail = null; // Initialize $mail outside the callback to make it accessible in catch blocks
    $auth->forgotPassword($_POST['email'], function ($selector, $token) use (&$mail) {
        $mail = new PHPMailer(true);
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to SMTP::DEBUG_SERVER for debugging
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($_POST['email']);
        $mail->addReplyTo(SMTP_REPLY_TO, SMTP_REPLY_TO_NAME);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="https://pmp.iheb.tn/reset-password?selector=' . urlencode($selector) . '&token=' . urlencode($token) . '">Réinitialiser le mot de passe</a>';
        $mail->AltBody = 'Visitez https://pmp.iheb.tn/reset-password?selector=' . urlencode($selector) . '&token=' . urlencode($token) . ' pour réinitialiser votre mot de passe.';

        $mail->send();
    });

    // Redirect or notify the user
    header('Location: /?t=verify');
    exit();
} catch (\Delight\Auth\InvalidEmailException $e) {
    header('Location: /forgot-password?error=invalidemail');
} catch (\Delight\Auth\EmailNotVerifiedException $e) {
    header('Location: /forgot-password?error=notverified');
} catch (\Delight\Auth\ResetDisabledException $e) {
    header('Location: /forgot-password?error=resetsdisabled');
} catch (\Delight\Auth\TooManyRequestsException $e) {
    header('Location: /forgot-password?error=toomanyrequests');
} catch (\PHPMailer\PHPMailer\Exception $e) {
    error_log("SMTP Error: " . ($mail ? $mail->ErrorInfo : $e->getMessage()));
    header('Location: /forgot-password?error=emailsend');
}
