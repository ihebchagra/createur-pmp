<?php
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_GET['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /dashboard');
    exit;
}

$stmt = $db->prepare('SELECT * FROM project_shares WHERE project_id = ?');
$stmt->execute([$_GET['project_id']]);
$shares = $stmt->fetchAll();

$content = <<<HTML
        <main class="container">
            <h1>Gérer les Partages</h1>
            <h3>Project: {$project['project_name']}</h3>

            <form method="post" action="/manage-shares">
                <input type="hidden" name="project_id" value="{$project['project_id']}">
                <input type="hidden" name="action" value="add">
                <label>Partager avec (email):</label>
                <input type="email" name="email" required>
                <button type="submit">Ajouter</button>
            </form>

            <h4>Partagé avec:</h4>
            <div>
    HTML;

foreach ($shares as $share) {
    $email = htmlspecialchars($share['shared_with_email']);
    $content .= <<<HTML
                <article class="share-item">
                    <p>{$email}</p>
                    <form method="post" action="/manage-shares" style="display: inline;">
                        <input type="hidden" name="project_id" value="{$project['project_id']}">
                        <input type="hidden" name="email" value="{$email}">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="secondary">Retirer</button>
                    </form>
                </article>
        HTML;
}

$content .= <<<HTML
            </div>
        </main>
        <script>
            document.title = "Gérer les Partages";
        </script>
    HTML;
