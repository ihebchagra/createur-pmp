<?php

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'unallowed':
            $error = 'Vous n\'êtes pas autorisé à supprimer cet utilisateur.';
            break;
        default:
            $error = 'Une erreur s\'est produite.';
            break;
    }
};

$content = <<<HTML
<div class="container">
    <div class="profile-info">
        <h2>Profil</h2>
        <p><strong>Email:</strong> {$email}</p>
    </div>
    <div class="actions">
    <form action="/logout" method="GET">
            <button type="submit">Se déconnecter</button>
        </form>
HTML;

if (isset($error)) {
    $content .= <<<HTML
<p style="color: red;">$error</p>
HTML;
}

$content .= <<<HTML
    </div>
</div>
<script>
        document.title = "Profil";
        </script>
HTML;