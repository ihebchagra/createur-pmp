<?php
try {
    $auth->confirmEmailAndSignIn($_GET['selector'], $_GET['token']);

    header('Location: /');
    exit();
} catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
    die('Invalid token');
} catch (\Delight\Auth\TokenExpiredException $e) {
    die('Token expired');
} catch (\Delight\Auth\UserAlreadyExistsException $e) {
    die('Email address already exists');
} catch (\Delight\Auth\TooManyRequestsException $e) {
    die('Too many requests');
}