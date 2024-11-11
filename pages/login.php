<?php
$error = '';

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'email':
            $error = 'Email invalide.';
            break;
        case 'password':
            $error = 'Mot de passe invalide.';
            break;
        case 'usernotfound':
            $error = 'Utilisateur non trouvé.';
            break;
        case 'toomanyrequests':
            $error = 'Trop de demandes. Veuillez réessayer plus tard.';
            break;
        case 'emailnotverified':
            $error = 'Email non vérifié. Veuillez vérifier votre email et votre dossier spam.';
            break;
    }
}
;

$content = <<<HTML
    <main class="container" x-data="{ password: '' }">
        <h2>Se connecter</h2>
        <form action="/login" method="post">
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" x-model="password" required><br>

            <label for="remember_me">
                <input type="checkbox" id="remember_me" name="remember_me">
                Se souvenir de moi
            </label><br>

    HTML;

if (!empty($error)) {
    $content .= <<<HTML
        <p style="color: red;">$error</p>
        HTML;
}

$content .= <<<HTML
            <input type="submit" value="Se connecter">
            <p><a href="/forgot-password">Mot de passe oublié ?</a></p>
        </form>
    </main>
    <script>
    document.title = "Se Connecter";
    </script>
    HTML;