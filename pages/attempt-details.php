<?php
// Get attempt
$stmt = $db->prepare('SELECT * FROM attempts WHERE attempt_id = ?');
$stmt->execute([$_GET['id']]);
$attempt = $stmt->fetch();

if (!$attempt) {
    header('Location: /dashboard');
    exit;
}

// Verify user has access to project
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$attempt['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /dashboard');
    exit;
}

// Get answered questions
$stmt = $db->prepare('
    SELECT q.* 
    FROM project_questions q 
    JOIN attempt_answers a ON q.question_id = a.question_id 
    WHERE a.attempt_id = ?
    ORDER BY q.question_id
');
$stmt->execute([$attempt['attempt_id']]);
$questions = $stmt->fetchAll();

$student_name = htmlspecialchars($attempt['student_name']);
$problem_text = htmlspecialchars($project['problem_text']);

$content .= <<<HTML
        <main class="container">
            <h1>Résultats de la tentative</h1>
            <div class="attempt-info">
                <h2>Étudiant : {$student_name}</h2>
                <h3>Score final : {$attempt['result']} points</h3>
                <p>Date : {$attempt['created_at']}</p>
            </div>

            <div class="problem">
                <h2>Énoncé : </h2>
                <p>{$problem_text}</p>
            </div>

            <div class="questions">
                <h2>Questions sélectionnées : </h2>
    HTML;

foreach ($questions as $question) {
    $points_text = match ($question['solution_points']) {
        '2' => 'Proposition obligatoire : +2 points',
        '1' => 'Proposition utile : +1 point',
        '0' => 'Proposition inutile : +0 points',
        'dead' => 'Proposition dangereuse, score final 0',
        '-2' => 'Proposition dangereuse : -2 points',
        '-1' => 'Proposition dangereuse : -1 point',
        default => 'erreur'
    };

    $class = match ($question['solution_points']) {
        'dead' => 'dead',
        '2', '1' => 'correct',
        '0' => 'null',
        '-2', '-1' => 'incorrect',
        default => ''
    };

    $content .= <<<HTML
                <article class="proposition revealed {$class}">
                    <div>
                        <h5>{$question['question_text']}</h5>
                    </div>
                    <div>
                        <p>{$question['solution_text']}</p>
                        <p class="solution_points">{$points_text}</p>
                    </div>
                </article>
        HTML;
}

$content .= <<<HTML
            </div>
        </main>
        <script>
            document.title = "Détails de la tentative - {$student_name}";
        </script>
    HTML;
