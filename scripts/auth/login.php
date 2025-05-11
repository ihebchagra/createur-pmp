<?php
try {
    if (isset($_POST['remember_me'])) {
        $rememberDuration = (int) (60 * 60 * 24 * 365.25);
    } else {
        $rememberDuration = null;
    }
    $auth->login($_POST['email'], $_POST['password'], $rememberDuration);

    // redirect to /
    header('Location: /');
    exit();
} catch (\Delight\Auth\InvalidEmailException $e) {
    header('Location: /login?error=email');
} catch (\Delight\Auth\InvalidPasswordException $e) {
    header('Location: /login?error=password');
} catch (\Delight\Auth\EmailNotVerifiedException $e) {
    header('Location: /login?error=emailnotverified');
} catch (\Delight\Auth\TooManyRequestsException $e) {
    header('Location: /login?error=toomanyrequests');
}