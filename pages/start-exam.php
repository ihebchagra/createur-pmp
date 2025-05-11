<?php
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_GET['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();
// if project does not exist or user does not have access to it
if (!$project) {
    header('Location: /dashboard');
    exit;
}

$project_id = htmlspecialchars($project['project_id']);

$content = <<<HTML
<main class="container">
<h1>Nouvelle Tentative</h1>
<form method="post" action="/create-attempt">
    <input type="hidden" name="project_id" value="{$project_id}">
    <div>
        <label for="student_name">Nom de l'étudiant:</label>
        <input type="text" id="student_name" name="student_name" required>
    </div>
    <button type="submit">Créer Tentative</button>
</form>
</main>
<script>
        document.title = "Nouvelle Tentative";
        </script>
HTML;
