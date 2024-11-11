<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../scripts/auth/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $projectId = $_POST['project_id'];
    $enonce = trim($_POST['enonce']);
    $questions = $_POST['questions'];

    if (!empty($enonce) && $auth->isLoggedIn()) {
        // Update project enonce
        $stmt = $db->prepare('UPDATE user_projects SET problem_text = ?, updated_at = NOW() WHERE project_id = ? AND user_id = ?');
        $stmt->execute([$enonce, $projectId, $auth->getUserId()]);

        // Remove all existing questions for the project
        $stmt = $db->prepare('DELETE FROM project_questions WHERE project_id = ?');
        $stmt->execute([$projectId]);

        // Reinsert all questions
        foreach ($questions as $question) {
            $stmt = $db->prepare('INSERT INTO project_questions (project_id, question_text, solution_text, solution_points) VALUES (?, ?, ?, ?)');
            $stmt->execute([$projectId, $question['text'], $question['solution'], $question['points']]);
        }
    }
}

header('Location: /edit?t=saved-project&project_id=' . $_POST['project_id']);
exit;