<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $request = parse_url($request, PHP_URL_PATH);
    if ($auth->isLoggedIn()) {
        switch ($request) {
            case '/dashboard':
            case '/':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/dashboard.php';
                break;
            case '/start-exam':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/start-exam.php';
                break;
            case '/attempt':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/attempt.php';
                break;
            case '/result':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/result.php';
                break;
            case '/results':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/results.php';
                break;
            case '/attempt-details':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/attempt-details.php';
                break;
            case '/logout':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/logout.php';
                break;
            case '/profile':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/profile.php';
                break;
            case '/edit':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/edit.php';
                break;
            case '/shares':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/shares.php';
                break;
            default:
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/404.php';
                break;
        }
    } else {
        switch ($request) {
            case '/':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/home.php';
                break;
            case '/register':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/register.php';
                break;
            case '/verify':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/verify.php';
                break;
            case '/forgot-password':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/forgot-password.php';
                break;
            case '/login':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/login.php';
                break;
            case '/reset-password':
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/reset-password.php';
                break;
            default:
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/404.php';
                break;
        }
    }
} elseif ($method == 'POST') {
    if ($auth->isLoggedIn()) {
        switch ($request) {
            case '/add-project':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/add-project.php';
                break;
            case '/create-attempt':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/create-attempt.php';
                break;
            case '/delete-project':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/delete-project.php';
                break;
                // DANGER
            case '/delete-user':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/delete_user.php';
                break;
            case '/save-project':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/save-project.php';
                break;
            case '/manage-shares':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/manage-shares.php';
                break;
            case '/save-attempt':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/save-attempt.php';
                break;
            default:
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/404.php';
                break;
        }
    } else {
        switch ($request) {
            case '/register':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/register.php';
                break;
            case '/login':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/login.php';
                break;
            case '/forgot-password':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/forgot-password.php';
                break;
            case '/reset-password':
                require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/reset-password.php';
                break;
            default:
                require $_SERVER['DOCUMENT_ROOT'] . '/../pages/404.php';
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créateur PMP</title>
    <script defer src="static/js/ajax.js"></script>
    <script defer src="static/js/autosize.js"></script>
    <script defer src="static/js/collapse.js"></script>
    <script defer src="static/js/persist.js"></script>
    <script defer src="static/js/autoanimate.js"></script>
    <script defer src="static/js/alpine.js"></script>
    <link rel="shortcut icon" href="static/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="static/css/pico.css">
    <link rel="stylesheet" href="static/css/main.css">
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/components/layout/toast.php'; ?>

    <div id="content" class="content">
        <?php if ($request != '/attempt') {
            require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/components/layout/navbar.php';
        }; ?>

        <?php
        echo $content;
        ?>
    </div>

    <footer class="footer">
        <p> <?php echo date('Y'); ?> Créateur PMP est un project open source publié sous la license GPL-3 - <a
                href="https://github.com/ihebchagra/createur-pmp" class="white-link">GitHub</a></p>
    </footer>
    <script src="static/js/modal.js"></script>
</body>

</html>
