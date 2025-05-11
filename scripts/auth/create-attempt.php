<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

// Verify POST request and required fields
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['project_id']) || !isset($_POST['student_name'])) {
    header('Location: /dashboard');
    exit;
}

// Verify user has access to this project
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_POST['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /dashboard');
    exit;
}

// Create new attempt
$stmt = $db->prepare('INSERT INTO attempts (student_name, project_id, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)');
$stmt->execute([
  trim($_POST['student_name']),
  $_POST['project_id']
]);

$attempt_id = $db->lastInsertId();

// Redirect to the attempt page
header("Location: /attempt?id=$attempt_id");
exit;
