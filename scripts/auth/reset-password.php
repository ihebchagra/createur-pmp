<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selector = $_POST['selector'] ?? '';
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($password !== $confirmPassword) {
        header('Location: /reset-password?error=passwordmismatch&selector=' . urlencode($selector) . '&token=' . urlencode($token));
        exit();
    }

    try {
        $auth->resetPassword($selector, $token, $password);
        header('Location: /login?t=reset');
        exit();
    } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        header('Location: /reset-password?error=invalidtoken');
    } catch (\Delight\Auth\TokenExpiredException $e) {
        header('Location: /reset-password?error=tokenexpired');
    } catch (\Delight\Auth\ResetDisabledException $e) {
        header('Location: /reset-password?error=resetsdisabled');
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        header('Location: /reset-password?error=invalidpassword');
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        header('Location: /reset-password?error=toomanyrequests');
    }
} else {
    header('Location: /');
    exit();
}
