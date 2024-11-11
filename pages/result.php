<?php
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_GET['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();
// if project does not exist or user does not have access to it
if (!$project) {
    header('Location: /dashboard');
    exit;
}

$stmt = $db->prepare('SELECT * FROM project_questions WHERE project_id = ?');
$stmt->execute([$_GET['project_id']]);
$questions = $stmt->fetchAll();
$json_questions = htmlspecialchars(json_encode($questions));

$stmt = $db->prepare('SELECT * FROM attempts WHERE attempt_id = ? AND project_id = ?');
$stmt->execute([$_GET['attempt_id'], $project['project_id']]);
$attempt = $stmt->fetch();
$result = $attempt['result'];
$student_name = htmlspecialchars($attempt['student_name']);

$project_id = htmlspecialchars($project['project_id']);

$content = <<<HTML
    <main class="container" x-data="{ questions: {$json_questions},
     total : 0,
     calculateTotal() {
            this.total = 0;
            for (let i = 0; i < this.questions.length; i++) {
                if (this.questions[i].solution_points === 'dead' || parseInt(this.questions[i].solution_points) < 0) {
                    continue;   
                }
                this.total += parseInt(this.questions[i].solution_points);
            }
        } }"
    x-init="calculateTotal()">
    <h1>Résultat : {$student_name}</h1>
    <p>Votre résultat est de : {$result} points</p>
    <p>Maximum des Points:&nbsp;<span x-text="total"></span>&nbsp;points</p>
    <form method="get" action="/start-exam">
        <input type="hidden" name="project_id" value="{$project['project_id']}">
        <button type="submit">Commencer une nouvelle tentative</button>
    </form>
    <form method="get" action="/">
        <button type="submit">Retour à la page d'acceuil</button>
    </form>
    </main>
    <script>
            document.title = "Résultat";
            </script>
    HTML;