<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

try {
    $userId = $auth->register($_POST['email'], $_POST['password'], null, function ($selector, $token) {
        $mail = new PHPMailer(true);
        // Server settings
        $mail->isSMTP();  // Send using SMTP
        $mail->Host = SMTP_HOST;  // Set the SMTP server to send through
        $mail->SMTPAuth = true;  // Enable SMTP authentication
        $mail->Username = SMTP_USER;  // SMTP username
        $mail->Password = SMTP_PASS;  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Enable implicit TLS encryption
        $mail->Port = SMTP_PORT;  // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($_POST['email']);  // Add a recipient
        $mail->addReplyTo(SMTP_REPLY_TO, SMTP_REPLY_TO_NAME);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Vérification de votre compte créateur PMP';
        $mail->Body = 'Merci de vous être inscrit sur Créateur PMP. Veuillez cliquer sur le lien suivant pour vérifier votre adresse e-mail : <a href="https://pmp.iheb.tn/verify?selector=' . urlencode($selector) . '&token=' . urlencode($token) . '">Vérifier votre adresse e-mail</a>';
        $mail->AltBody = 'Merci de vous être inscrit sur Créateur PMP. Veuillez cliquer sur le lien suivant pour vérifier votre adresse e-mail : https://pmp.iheb.tn/verify?selector=' . urlencode($selector) . '&token=' . urlencode($token);

        $mail->send();
    });

    $sql = 'INSERT INTO user_profiles (user_id, lastname, firstname) VALUES (:user_id, :lastname, :firstname)';
    $stmt = $db->prepare($sql);
    $stmt->execute(['user_id' => $userId, 'lastname' => $_POST['nom'], 'firstname' => $_POST['prenom']]);

    header('Location: /?t=verify');
    exit();
} catch (\Delight\Auth\InvalidEmailException $e) {
    header('Location: /register?error=email');
} catch (\Delight\Auth\InvalidPasswordException $e) {
    header('Location: /register?error=password');
} catch (\Delight\Auth\UserAlreadyExistsException $e) {
    header('Location: /register?error=userexists');
} catch (\Delight\Auth\TooManyRequestsException $e) {
    header('Location: /register?error=toomanyrequests');
} catch (\PHPMailer\PHPMailer\Exception $e) {
    header('Location: /register?error=emailerror');
}
