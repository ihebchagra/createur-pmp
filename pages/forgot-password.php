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
        case 'userexists':
            $error = "L'utilisateur existe déjà.";
            break;
        case 'toomanyrequests':
            $error = 'Trop de demandes. Veuillez réessayer plus tard.';
            break;
        case 'emailerror':
            $error = "Erreur lors d'envoi de l'email de vérification.";
            break;
        case 'invalidemail':
            $error = 'Adresse email invalide.';
            break;
        case 'notverified':
            $error = 'Email non vérifié.';
            break;
        case 'resetsdisabled':
            $error = 'La réinitialisation du mot de passe est désactivée.';
            break;
        case 'emailsend':
            $error = "Erreur lors de l'envoi de l'email.";
            break;
        case 'passwordmismatch':
            $error = 'Les mots de passe ne correspondent pas.';
            break;
        case 'invalidtoken':
            $error = 'Token invalide.';
            break;
        case 'tokenexpired':
            $error = 'Le token a expiré.';
            break;
        case 'invalidpassword':
            $error = 'Mot de passe invalide.';
            break;
    }
};

$content = <<<HTML
    <main class="container" x-data="{ email: '' }">
        <h2>Mot de passe oublié</h2>
        <form action="/forgot-password" method="post">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" x-model="email" required><br>

    HTML;

if (!empty($error)) {
    $content .= <<<HTML
        <p style="color: red;">$error</p>
        HTML;
}

$content .= <<<HTML
            <input type="submit" value="Réinitialiser le mot de passe" :disabled="email === ''">
            </form>
        </main>
        <script>
        document.title = "Mot de passe oublié";
        </script>
    HTML;
