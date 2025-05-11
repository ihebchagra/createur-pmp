<?php
// Verify user has access to project
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_GET['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /dashboard');
    exit;
}

// Get all attempts for this project
$stmt = $db->prepare('SELECT * FROM attempts WHERE project_id = ? ORDER BY created_at DESC');
$stmt->execute([$_GET['project_id']]);
$attempts = $stmt->fetchAll();

$content = <<<HTML
        <main class="container">
            <h1>Résultats - {$project['project_name']}</h1>
            
            <table role="grid">
                <thead>
                    <tr>
                        <th scope="col">Étudiant</th>
                        <th scope="col">Score</th>
                        <th scope="col">Date</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
    HTML;

foreach ($attempts as $attempt) {
    $date = date('d/m/Y H:i', strtotime($attempt['created_at']));
    $status = $attempt['locked'] ? 'Terminé' : 'En cours';
    $score = $attempt['result'] !== null ? $attempt['result'] : '-';

    $content .= <<<HTML
                    <tr>
                        <td>{$attempt['student_name']}</td>
                        <td>{$score}</td>
                        <td>{$date}</td>
                        <td>{$status}</td>
                        <td>
                            <a href="/attempt-details?id={$attempt['attempt_id']}" class="button">Voir détails</a>
                        </td>
                    </tr>
        HTML;
}

$content .= <<<HTML
                </tbody>
            </table>
        </main>
        <script>
            document.title = "Résultats - {$project['project_name']}";
        </script>
    HTML;
