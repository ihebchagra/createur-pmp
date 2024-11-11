<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

$host = DB_HOST;
$db = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$charset = DB_CHARSET;

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $db = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

$auth = new \Delight\Auth\Auth($db);

function getUserInfo(\Delight\Auth\Auth $auth, PDO $db)
{
    if (!$auth->isLoggedIn()) {
        return null;
    }

    if (!isset($_SESSION['_internal_user_info'])) {
        $stmt = $db->prepare('SELECT * FROM user_profiles WHERE user_id = ?');
        $stmt->execute([$auth->getUserId()]);
        $_SESSION['_internal_user_info'] = $stmt->fetch();
    }

    if (!isset($_SESSION['_internal_user_projects'])) {
        $stmt = $db->prepare('SELECT * FROM user_projects WHERE user_id = ?');
        $stmt->execute([$auth->getUserId()]);
        $_SESSION['_internal_user_projects'] = $stmt->fetchAll();
    }

    return [
        'profile' => $_SESSION['_internal_user_info'],
        'projects' => $_SESSION['_internal_user_projects']
    ];
}

if ($auth->isLoggedIn()) {
    $userId = $auth->getUserId();
    $email = $auth->getEmail();
    $userInfo = getUserInfo($auth, $db);
    $lastname = $userInfo['profile']['lastname'];
    $firstname = $userInfo['profile']['firstname'];
}
