<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attempt_id']) && isset($_POST['total']) && isset($_POST['questions'])) {
    $questions = $_POST['questions'];
    $total = $_POST['total'];
    $attemptId = $_POST['attempt_id'];

    if ($auth->isLoggedIn()) {
        // Update project enonce
        $stmt = $db->prepare('UPDATE attempts SET locked = 1, result = ? WHERE attempt_id = ?');
        $stmt->execute([$total, $attemptId]);

        foreach ($questions as $question) {
            $stmt = $db->prepare('INSERT INTO attempt_answers (attempt_id, question_id) VALUES (?, ?)');
            $stmt->execute([$attemptId, $question]);
        }
    }
}

header('Location: /result?project_id=' . $_POST['project_id'] . '&attempt_id=' . $_POST['attempt_id']);
exit;