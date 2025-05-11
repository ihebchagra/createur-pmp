<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id']) && isset($_POST['action'])) {
    // Verify project ownership
    $stmt = $db->prepare('SELECT user_id FROM user_projects WHERE project_id = ?');
    $stmt->execute([$_POST['project_id']]);
    $project = $stmt->fetch();

    if ($project && $project['user_id'] == $auth->getUserId()) {
        if ($_POST['action'] === 'add' && isset($_POST['email'])) {
            $stmt = $db->prepare('INSERT OR IGNORE INTO project_shares (project_id, shared_with_email) VALUES (?, ?)');
            $stmt->execute([$_POST['project_id'], $_POST['email']]);
        } elseif ($_POST['action'] === 'remove' && isset($_POST['email'])) {
            $stmt = $db->prepare('DELETE FROM project_shares WHERE project_id = ? AND shared_with_email = ?');
            $stmt->execute([$_POST['project_id'], $_POST['email']]);
        }
    }
}

header('Location: /shares?project_id=' . $_POST['project_id']);
exit;
