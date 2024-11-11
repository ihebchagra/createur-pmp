<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_name'])) {
    $projectName = trim($_POST['project_name']);
    if (!empty($projectName) && $auth->isLoggedIn()) {
        $stmt = $db->prepare('INSERT INTO user_projects (user_id, project_name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())');
        $stmt->execute([$auth->getUserId(), $projectName]);
        // Refresh the session data
        $stmt = $db->prepare('SELECT * FROM user_projects WHERE user_id = ?');
        $stmt->execute([$auth->getUserId()]);
        $_SESSION['_internal_user_projects'] = $stmt->fetchAll();
    }
}

header('Location: /');
exit;