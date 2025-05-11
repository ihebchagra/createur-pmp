<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $projectId = intval($_POST['project_id']);
    if ($projectId > 0 && $auth->isLoggedIn()) {
        $stmt = $db->prepare('DELETE FROM user_projects WHERE user_id = ? AND project_id = ?');
        $stmt->execute([$auth->getUserId(), $projectId]);
        // Refresh the session data
        $stmt = $db->prepare('SELECT * FROM user_projects WHERE user_id = ?');
        $stmt->execute([$auth->getUserId()]);
        $_SESSION['_internal_user_projects'] = $stmt->fetchAll();
    }
}

header('Location: /?t=deleted-project');
exit;