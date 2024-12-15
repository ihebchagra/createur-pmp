<?php
session_start();
$auth->logOut();

// Clear session data
session_unset();
session_destroy();

header('Location: /');
exit();
